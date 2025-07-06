<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Testing\TestResponse;

class TeamSubmitQuestionResponseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::now());
    }

    protected function tearDown(): void
    {
        Date::setTestNow();
        parent::tearDown();
    }

    protected function createTeamWithMember(): array
    {
        $team = create(Team::class);
        $user = create(User::class);
        $team->members()->attach($user);
        return [$team, $user];
    }

    protected function createEventWithQuiz(array $quizAttributes = []): array
    {
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, array_merge([
            'event_id' => $event->id,
            'token' => 'valid_token'
        ], $quizAttributes));
        return [$event, $quiz];
    }

    protected function verifyQuiz(Quiz $quiz, string $token = 'valid_token'): void
    {
        Session::put('quiz_token', $token);
        $quiz->verify($token);
    }

    #[Test]
    public function user_can_submit_response_within_quiz_time_limit_if_they_have_started_quiz()
    {
        $questions = create(Question::class, 10);
        [$event, $quiz] = $this->createEventWithQuiz([
            'slug' => 'test-quiz',
            'time_limit' => 60
        ]);
        $quiz->questions()->saveMany($questions);

        [$team, $user] = $this->createTeamWithMember();
        $event->teams()->attach($team);

        create(QuizResponse::class, 1, [
            'quiz_id' => $quiz->id,
            'team_id' => $team->id,
            'started_at' => Date::now()
        ]);

        $quiz->setActive();
        $this->signIn($user);
        $this->verifyQuiz($quiz);

        $response = $this->submitSeed([
            'quiz_id' => $quiz->id,
            'question' => $questions[0]
        ]);

        $json = $response->json();
        $this->assertEquals('success', $json['status']);
        $this->assertStringContainsString('Your response has been recorded', $json['message']);

        $quizResponse = $quiz->participations()->where('team_id', $team->id)->first()->fresh();
        $this->assertInstanceOf(\DateTimeInterface::class, $quizResponse->finished_at);
        $this->assertInstanceOf(Collection::class, $quizResponse->responses);
    }

    #[Test]
    public function if_user_submits_another_response_error_is_shown()
    {
        $questions = create(Question::class, 10);
        [$event, $quiz] = $this->createEventWithQuiz();
        $quiz->questions()->saveMany($questions);

        [$team, $user] = $this->createTeamWithMember();
        $event->teams()->attach($team);

        create(QuizResponse::class, 1, [
            'quiz_id' => $quiz->id,
            'team_id' => $team->id,
            'started_at' => Date::now(),
            'finished_at' => Date::now() // Mark as already finished
        ]);

        $quiz->setActive();
        $this->signIn($user);
        $this->verifyQuiz($quiz);

        $response = $this->submitSeed([
            'quiz_id' => $quiz->id,
            'question' => $questions[0]
        ]);

        $json = $response->json();
        $this->assertEquals('error', $json['status']);
        $this->assertStringContainsString('already taken', $json['message']);
    }

    #[Test]
    public function if_user_submits_response_5_min_later_than_time_limit_response_is_recorded_but_team_is_disqualified()
    {
        $questions = create(Question::class, 10);
        [$event, $quiz] = $this->createEventWithQuiz([
            'time_limit' => 60
        ]);
        $quiz->questions()->saveMany($questions);

        [$team, $user] = $this->createTeamWithMember();
        $event->teams()->attach($team);

        $quizResponse = create(QuizResponse::class, 1, [
            'quiz_id' => $quiz->id,
            'team_id' => $team->id,
            'started_at' => Date::now()
        ]);

        $quiz->setActive();
        $this->signIn($user);
        $this->verifyQuiz($quiz);

        // Save a response first
        $questionResponse = create(\App\Models\QuestionResponse::class, 1, [
            'quiz_response_id' => $quizResponse->id,
            'question_id' => $questions[0]->id,
            'response_keys' => 'test_response'
        ]);

        // Simulate time passing beyond limit
        Date::setTestNow(Date::now()->addMinutes(65));

        $response = $this->submitSeed([
            'quiz_id' => $quiz->id,
            'question' => $questions[0]
        ]);

        $json = $response->json();
        $this->assertEquals('error', $json['status']);
        $this->assertStringContainsString('time limit exceed', $json['message']);

        $quizResponse = $quizResponse->fresh();
        $this->assertEqualsWithDelta(
            Date::now()->getTimestamp(),
            $quizResponse->finished_at->getTimestamp(),
            2,
            'Time difference does not match'
        );
        $this->assertCount(1, $quizResponse->responses);
    }

    #[Test]
    public function user_cannot_submit_response_if_they_have_not_verified_quiz_token()
    {
        [$event, $quiz] = $this->createEventWithQuiz();
        $question = create(Question::class, 1);
        $quiz->questions()->save($question);

        [$team, $user] = $this->createTeamWithMember();
        $event->teams()->attach($team);

        create(QuizResponse::class, 1, [
            'quiz_id' => $quiz->id,
            'team_id' => $team->id,
            'started_at' => Date::now()
        ]);

        $quiz->setActive();
        $this->signIn($user);
        // Note: Not calling verifyQuiz() here since we're testing the unverified case

        $response = $this->submit($question);

        $json = $response->json();
        $this->assertEquals('error', $json['status']);
        $this->assertStringContainsString('not verified', $json['message']);
    }

    private function submit($question): TestResponse
    {
        return $this->postJson(
            route('quizzes.response.store', $question->quiz),
            [
                'question_id' => $question->id,
                'response_key' => 'wrl28'
            ]
        );
    }

    private function submitSeed($args = []): TestResponse
    {
        $quiz = $args['quiz_id'] ?? create(Quiz::class, 1);
        $question = $args['question'] ?? create(Question::class, 1);

        if ($quiz && is_numeric($quiz)) {
            $quiz = Quiz::find($quiz);
        }

        // Ensure question is associated with quiz
        if (!$quiz->questions->contains($question)) {
            $quiz->questions()->save($question);
        }

        return $this->submit($question);
    }
}
