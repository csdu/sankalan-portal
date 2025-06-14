<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AllowTeamsForQuizTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_allows_participating_teams_for_active_quiz()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('some team', $users[1]);
        $events = create(Event::class, 2)->each(function ($event) {
            create(Quiz::class, 1, ['event_id' => $event->id])->setActive();
        });

        $team->participate($events->first());

        $this->withoutExceptionHandling()->signInAdmin();

        $this->postJson(
            route('admin.events.teams.allow-active-quiz', [$events->first(), $team])
        )->assertRedirect();

        tap($events->first()->fresh()->activeQuiz, function ($activeQuiz) {
            $this->assertCount(1, $activeQuiz->teams()->get());
        });
    }

    #[Test]
    public function admin_gets_error_when_making_teams_participate_for_active_quiz_if_no_active_quiz_is_there()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('some team', $users[1]);

        $events = create(Event::class, 2)->each(function ($event) {
            create(Quiz::class, 1, ['event_id' => $event->id]);
        }); // Quiz is not active

        $team->participate($events->first());

        $this->withoutExceptionHandling()->signInAdmin();

        $route = route('admin.events.teams.allow-active-quiz', [$events->first(), $team]);
        $this->postJson($route)->assertRedirect()->assertSessionHas('flash_notification.0.level', 'danger');

        tap($events->first()->quizzes->first()->fresh(), function ($activeQuiz) {
            $this->assertCount(0, $activeQuiz->teams()->get());
        });
    }
}
