<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Event;
use App\Quiz;
use App\Question;
use App\AnswerChoice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class TeamSubmitQuizResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_submit_response_within_quiz_time_limit_if_they_have_started_quiz()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(AnswerChoice::class, 4, ['question_id' => $question->id]);
        });

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        $quiz->allowTeam($team);

        $team->beginQuiz($quiz);

        $responses = $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->random()->key,
            ];
        })->toArray();

        Carbon::setTestNow(now()->addSeconds($quiz->timeLimit - 60)); //Submit 60secs before timeout.

        $json = $this->postJson(
            route('quizzes.response.store', $quiz),
            ['responses' => $responses]
        )->assertSuccessful()->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertEquals('success', $json['message']['level']);

        $quizParticipation = $quiz->participationByTeam($team);
        $this->assertInstanceOf(Carbon::class, $quizParticipation->finished_at);
        $this->assertInstanceOf(Collection::class, $quizParticipation->responses);
        $this->assertCount(10, $quizParticipation->responses);
    }

    /** @test */
    public function if_user_sumbits_another_response_warning_message_is_shown()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(AnswerChoice::class, 4, ['question_id' => $question->id]);
        });

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        $quiz->allowTeam($team);

        $team->beginQuiz($quiz);

        $responses = $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->random()->key,
            ];
        })->toArray();

        Carbon::setTestNow(now()->addMinutes(20)); //fast forward time 20Mins.

        $team->endQuiz($quiz, $responses);

        Carbon::setTestNow(now()->addMinutes(5)); //after 5 min try submitting other response

        $json = $this->postJson( route('quizzes.response.store', $quiz), [
            'responses' => $responses
        ])->assertStatus(Response::HTTP_FORBIDDEN)
        ->json();

        $this->assertEquals('warning', $json['message']['level']);
        $quizParticipation = $quiz->participationByTeam($team);
        $this->assertEquals(5, $quizParticipation->finished_at->diffInMinutes(now()), 'Time Difference does not match');
        $this->assertCount(10, $quizParticipation->responses);
    }

    /** @test */
    public function if_user_sumbits_response_5_min_later_than_time_limit_response_is_recorded_but_team_is_disqualified()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id])->fresh();
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(AnswerChoice::class, 4, ['question_id' => $question->id]);
        });

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        $quiz->allowTeam($team);

        $team->beginQuiz($quiz);

        $responses = $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->random()->key,
            ];
        })->toArray();

        //fast forward time to exceed time limit by 5 mins.
        Carbon::setTestNow(now()->addMinutes($quiz->timeLimit + 5));

        $json = $this->postJson(route('quizzes.response.store', $quiz), [
            'responses' => $responses
        ])->assertStatus(Response::HTTP_REQUEST_TIMEOUT)
        ->json();

        $this->assertEquals('danger', $json['message']['level']);
        $this->assertContains('disqualified', $json['message']['message']);

        $quizParticipation = $quiz->participationByTeam($team);
        $this->assertEquals(0, $quizParticipation->finished_at->diffInMinutes(now()), 'Time Difference does not match');
        $this->assertCount(10, $quizParticipation->responses);
    }

    /** @test */
    public function user_cannot_submit_response_if_his_team_is_not_participating_in_the_event()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $quiz->setActive();
        // $team->participate($event); //They're not participating
        // $quiz->allowTeam($team); //They're not allowed to take Quiz

        $json = $this->postJson(route('quizzes.response.store', $quiz))
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertEquals('danger', $json['message']['level']);

    }

    /** @test */
    public function user_cannot_submit_response_if_quiz_is_not_active()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        // $quiz->setActive(); // Quiz is not active
        // $quiz->allowTeam($team); // They're not allowed to take Quiz

        $json = $this->postJson(route('quizzes.response.store', $quiz))
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertEquals('danger', $json['message']['level']);
    }


    /** @test */
    public function user_cannot_submit_response_if_their_team_is_not_allowed_for_quiz()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        // $quiz->allowTeam($team); // They're not allowed to take Quiz

        $json = $this->postJson(route('quizzes.response.store', $quiz))
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertEquals('danger', $json['message']['level']);
    }

    /** @test */
    public function user_cannot_submit_response_if_they_have_not_yet_started()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);

        $this->withoutExceptionHandling()->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();
        $quiz->allowTeam($team); // They're not allowed to take Quiz

        // $quiz->begin($team); //They've not yet started taking quiz, They're trying something fishhy.

        $json = $this->postJson(route('quizzes.response.store', $quiz))
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->json();

        $this->assertArrayHasKey('message', $json);
        $this->assertEquals('danger', $json['message']['level']);
    }
}
