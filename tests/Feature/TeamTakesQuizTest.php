<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TeamTakesQuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_participating_teams_are_redirected_to_dashboard_with_appropriate_error_message()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $events = create(Event::class, 2);
        $team->participate($events[0]);
        $quiz = create(Quiz::class, 1, ['event_id' => $events[1]->id]);
        $quiz->setActive();

        $this->be($users[0]);

        $this->get(route('quizzes.take', $quiz))
            ->assertRedirect('/')
            ->assertSessionHas('flash_notification');

        $this->assertEquals('warning', Session::get('flash_notification')->first()->level);
    }

    /** @test */
    public function participating_teams_are_redirected_with_error_if_quiz_is_not_active()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $team->participate($event);

        $this->be($users[0]);

        $this->get(route('quizzes.take', $quiz))
            ->assertRedirect('/')
            ->assertSessionHas('flash_notification');

        $this->assertEquals('warning', Session::get('flash_notification')->first()->level);
    }

    /** @test */
    public function user_should_redirect_to_quiz_verify_page_if_quiz_is_not_verified()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => 'valid_token']);

        $this->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $this->get(route('quizzes.take', $quiz))
            ->assertRedirect(route('quizzes.verify', $quiz));
    }

    /** @test */
    public function test_user_can_take_quiz_if_quiz_is_verified()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => $token = '1234567']);

        $this->be($user)->withoutExceptionHandling();

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $this->withSession(['quiz_token' => $token])
            ->get(route('quizzes.take', $quiz))
            ->assertSuccessful();
    }

    /** @test */
    public function team_starts_quiz_and_revisits_or_reload_after_sometime_before_their_timer_is_gone_they_can_take_quiz_within_remaining_time()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $event = create(Event::class);
        $team->participate($event);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => $token = 'valid_token']);

        $quiz->setActive();
        $startTime = now();
        $team->beginQuiz($quiz);

        $this->withoutExceptionHandling()->be($users[0]);

        Carbon::setTestNow(now()->addMinutes(10));

        $viewParticipation = $this->withSession(['quiz_token' => $token])
            ->get(route('quizzes.take', $quiz))
            ->assertSuccessful()
            ->assertViewIs('quizzes.show')
            ->viewData('participation');

        $this->assertInstanceOf(QuizResponse::class, $viewParticipation);

        $this->assertArrayHasKey('timeLeft', $viewParticipation->toArray());
        $this->assertEquals($startTime->getTimestamp(), $viewParticipation->started_at->getTimestamp());
        $this->assertEquals($quiz->time_limit - (10 * 60), $viewParticipation->timeLeft);
    }

    /** @test */
    public function once_user_submits_quiz_they_cannot_revisit_quiz_and_redirected_to_dashboard()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user);

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

        // End Quiz 5 minutes before
        Carbon::setTestNow(now()->addSeconds($quiz->timeLeft - 5 * 60));

        $team->endQuiz($quiz, $responses);

        $this->get(route('quizzes.take', $quiz))
            ->assertRedirect('/')
            ->assertSessionHas('flash_notification');

        $this->assertEquals('warning', Session::get('flash_notification')->first()->level);
        $this->assertStringContainsString('already taken', Session::get('flash_notification')->first()->message);
    }
}
