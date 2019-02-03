<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Event;
use App\Quiz;
use Symfony\Component\HttpFoundation\Response;

class AllowTeamsForQuizTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_allows_participating_teams_for_active_quiz()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('some team', $users[1]);
        $events = create(Event::class, 2)->each(function($event) {
            create(Quiz::class, 1, ['event_id' => $event->id])->setActive();
        });

        $team->participate($events->first());

        $this->withoutExceptionHandling()->signInAdmin();

        $response = $this->postJson(
            route('admin.events.teams.allow-active-quiz', [$events->first(), $team])
        )->assertSuccessful()->json();

        tap($events->first()->fresh()->activeQuiz, function($activeQuiz) {
            $this->assertCount(1, $activeQuiz->teams()->get());
        });

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('success', $response['status']);

    }

    /** @test */
    public function admin_gets_error_when_making_teams_participate_for_active_quiz_if_no_active_quiz_is_there()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('some team', $users[1]);

        $events = create(Event::class, 2)->each(function ($event) {
            create(Quiz::class, 1, ['event_id' => $event->id]);
        }); // Quiz is not active

        $team->participate($events->first());

        $this->withoutExceptionHandling()->signInAdmin();

        $response = $this->postJson(
            route('admin.events.teams.allow-active-quiz', [$events->first(), $team])
        )->assertStatus(Response::HTTP_BAD_REQUEST)->json();

        tap($events->first()->quizzes->first()->fresh(), function ($activeQuiz) {
            $this->assertCount(0, $activeQuiz->teams()->get());
        });

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('error', $response['status']);
    }
}
