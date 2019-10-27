<?php

namespace Tests\Feature;

use App\Models\QuestionOption;
use App\Models\Event;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TeamSubmitQuestionResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_submit_response_within_quiz_time_limit_if_they_have_started_quiz()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => $token = 'valid_token']);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $team->beginQuiz($quiz);

        $responses = $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->random()->key,
            ];
        })->toArray();

        Carbon::setTestNow(now()->addSeconds($quiz->timeLimit - 60));
        // Fast Forward time, 60secs before timeout.

        $json = $this->withSession(['quiz_token' => $token])->postJson(
            route('quizzes.response.store', $quiz),
            ['responses' => $responses]
        )->assertSuccessful()->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertEquals('success', $json['message']['level']);

        $quizResponse = $quiz->participationByTeam($team);
        $this->assertInstanceOf(Carbon::class, $quizResponse->finished_at);
        $this->assertInstanceOf(Collection::class, $quizResponse->responses);
        $this->assertCount(10, $quizResponse->responses);
    }

    /** @test */
    public function if_user_sumbits_another_response_error_is_shown()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => $token = 'valid_token']);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        $quiz->verify($token);
        $team->beginQuiz($quiz);

        $responses = $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->random()->key,
            ];
        })->toArray();

        Carbon::setTestNow(now()->addMinutes(20)); //fast forward time 20Mins.

        $team->endQuiz($quiz, $responses);

        Carbon::setTestNow(now()->addMinutes(5)); // after 5 min try submitting other response

        $json = $this->postJson(route('quizzes.response.store', $quiz), [
                'responses' => $responses,
            ])->assertStatus(401)
            ->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertStringContainsString('already taken', $json['message']);
    }

    /** @test */
    public function if_user_sumbits_response_5_min_later_than_time_limit_response_is_recorded_but_team_is_disqualified()
    {
        $user = create(User::class);
        $event = create(Event::class);

        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => $token = 'valid_token']);

        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $quiz->verify($token);
        $team->beginQuiz($quiz);

        $responses = $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->random()->key,
            ];
        })->toArray();

        //fast forward time to exceed time limit by 5 mins.
        Carbon::setTestNow(now()->addMinutes($quiz->time_limit + 5 * 60));

        $json = $this->withoutExceptionHandling()
            ->postJson(route('quizzes.response.store', $quiz), [
                'responses' => $responses,
            ])->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->json();

        $this->assertEquals('danger', $json['message']['level']);
        $this->assertStringContainsString('time limit exceed', $json['message']['message']);

        $quizResponse = $quiz->participationByTeam($team);
        $this->assertEquals(0, $quizResponse->finished_at->diffInMinutes(now()), 'Time Difference does not match');
        $this->assertCount(10, $quizResponse->responses);
    }

    /** @test */
    public function user_cannot_submit_response_if_they_have_not_verified_quiz_token()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => 'valid_token']);

        $this->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        // $quiz->verify('valid_token'); // They've not verified quiz token.

        $json = $this->postJson(route('quizzes.response.store', $quiz))
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertStringContainsString('not verified quiz token', $json['message']);

        $this->assertArrayHasKey('verification_url', $json);
        $this->assertEquals(route('quizzes.verify', $quiz), $json['verification_url']);
    }
}
