<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Quiz;
use App\Event;
use Illuminate\Support\Facades\Session;
use App\Question;
use Carbon\Carbon;
use App\AnswerChoice;
use App\QuizParticipation;

class TeamTakesQuizTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function non_participating_teams_are_redirected_to_dashboard_with_appropriate_error_message()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $events = create(Event::class,2);
        $team->participate($events[0]);
        $quiz = create(Quiz::class, 1, ['event_id' => $events[1]->id]);
        $quiz->setActive();

        $this->withoutExceptionHandling()->be($users[0]);

        $response = $this->get(route('quizzes.take', $quiz));

        $response->assertRedirect()->assertSessionHas('flash_notification');
        $this->assertEquals('danger', Session::get('flash_notification')->first()->level);
        $this->assertContains('not participating', Session::get('flash_notification')->first()->message);
    }

    /** @test */
    public function participating_teams_are_redirected_with_error_if_quiz_is_not_active()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $team->participate($event);

        $this->withoutExceptionHandling()->be($users[0]);

        $response = $this->get(route('quizzes.take', $quiz));

        $response->assertRedirect()->assertSessionHas('flash_notification');
        
        $this->assertEquals('danger', Session::get('flash_notification')->first()->level);
    }

    /** @test */
    public function participating_teams_are_cannot_take_active_quiz_if_they_are_not_yet_a_quiz_participant()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $event = create(Event::class);
        $team->participate($event);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $quiz->setActive();

        $this->withoutExceptionHandling()->be($users[0]);

        $response = $this->get(route('quizzes.take', $quiz));

        $response->assertRedirect()->assertSessionHas('flash_notification');
        $this->assertEquals('danger', Session::get('flash_notification')->first()->level);
        $this->assertContains('not allowed', Session::get('flash_notification')->first()->message);
    }

    /** @test */
    public function participating_teams_are_can_take_active_quiz_if_they_are_marked_as_quiz_participant()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $event = create(Event::class);
        $team->participate($event);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        create(Question::class, 10, ['quiz_id' => $quiz->id])
            ->each(function($question) {
                create(AnswerChoice::class, 4, ['question_id' => $question->id]);
            });

        $quiz->setActive();
        $quiz->allowTeam($team);

        $this->withoutExceptionHandling()->be($users[0]);

        $response = $this->get(route('quizzes.take', $quiz));

        $response->assertSuccessful()->assertViewIs('quiz.index');
            
        $viewQuiz = $response->viewData('quiz');
        
        $this->assertInstanceOf(Quiz::class, $viewQuiz);

        $this->assertArrayHasKey('participations', $viewQuiz->toArray());
        $this->assertCount(1, $viewQuiz->participations);
        $this->assertNull($viewQuiz->participations->first()->started_at);
        tap($viewQuiz->participations->first()->fresh(), function($participation) {
            $this->assertInstanceOf(Carbon::class, $participation->started_at);
            $this->assertEquals(0, $participation->started_at->diffInSeconds(now()));
        });
        
        $this->assertArrayHasKey('questions', $viewQuiz->toArray());
        $this->assertCount(10, $viewQuiz->questions);
        $this->assertArrayHasKey('choices', $viewQuiz->questions->first()->toArray());
        $this->assertCount(4, $viewQuiz->questions->first()->choices);
    }

    /** @test */
    public function team_starts_quiz_and_revisits_or_reload_after_sometime_before_their_timer_is_gone_they_can_take_quiz_within_remaining_time()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $event = create(Event::class);
        $team->participate($event);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id])->fresh();

        $quiz->setActive();
        $quiz->allowTeam($team);

        $startTime = now();
        $team->beginQuiz($quiz);
        

        $this->withoutExceptionHandling()->be($users[0]);

        Carbon::setTestNow(now()->addMinutes(10));
        
        $response = $this->get(route('quizzes.take', $quiz));

        $response->assertSuccessful()->assertViewIs('quiz.index');

        $viewQuiz = $response->viewData('quiz');

        $this->assertInstanceOf(Quiz::class, $viewQuiz);

        $this->assertArrayHasKey('timeLeft', $viewQuiz->participations->toArray()[0]);
        tap($viewQuiz->participations->first()->fresh(), function($participation) use ($viewQuiz, $startTime){
            $this->assertEquals($startTime->getTimestamp(), $participation->started_at->getTimestamp());
            $this->assertEquals($viewQuiz->timeLimit-(10*60), $participation->timeLeft);
        });
    }
}
