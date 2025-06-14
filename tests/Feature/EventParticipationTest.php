<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventParticipationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_participate_in_any_event_as_a_single_member_team_when_he_has_no_team()
    {
        $event = create(Event::class);
        $user = create(User::class);

        $this->withoutExceptionHandling()->be($user);

        $this->assertCount(0, $event->fresh()->teams);

        $this->post(route('events.participate', $event));

        $team = $user->fresh()->individualTeam;

        // assert new individualTeam is created
        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals(1, $team->id);

        //assert participation is saved
        tap($event->fresh()->teams, function ($participatingTeams) use ($team) {
            $this->assertCount(1, $participatingTeams);
            $this->assertEquals($participatingTeams->first()->id, $team->id);
        });
    }

    #[Test]
    public function user_can_participate_in_any_event_as_a_single_member_team_when_he_has_a_single_member_team()
    {
        $event = create(Event::class);
        $user = create(User::class);
        $user2 = create(User::class);

        $twoMembersTeam = $user->createTeam('Two Members Rock', $user2->id);
        $team = $user->createTeam($teamName = 'Team Name');

        $this->withoutExceptionHandling()->be($user);

        $this->assertCount(0, $event->fresh()->teams);

        $this->post(route('events.participate', $event));

        // assert participation is saved & individualTeam is selected implicitly
        tap($event->fresh()->teams, function ($participatingTeams) use ($team) {
            $this->assertCount(1, $participatingTeams);
            $this->assertEquals($participatingTeams->first()->id, $team->id);
        });
    }

    #[Test]
    public function user_can_participate_in_any_event_as_a_team_when_he_specifies_team_explicitly()
    {
        $event = create(Event::class);
        $user = create(User::class);
        $user2 = create(User::class);
        $user3 = create(User::class);

        $otherTeam = $user->createTeam('Team Name', $user3->id);
        $team = $user->createTeam('Two Members Rock', $user2->id);

        $this->withoutExceptionHandling()->be($user);

        $this->assertCount(0, $event->fresh()->teams);

        $this->post(route('events.participate', $event), [
            'team_id' => $team->id,
        ]);

        // assert participation is saved & individualTeam is selected implicitly
        tap($event->fresh()->teams, function ($participatingTeams) use ($team) {
            $this->assertCount(1, $participatingTeams);
            $this->assertEquals($participatingTeams->first()->id, $team->id);
        });
    }

    #[Test]
    public function a_team_can_participate_in_multiple_different_events()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('EK or EK GYARAH', $users[1]);
        $events = create(Event::class, 2);
        $team->participate($events[0]);

        $this->be($users[0]);

        $this->assertCount(1, $team->events()->get());

        $this->post(route('events.participate', $events[1]), [
            'team_id' => $team->id,
        ]);

        $this->assertCount(2, $team->events()->get());
        $this->assertSame(
            $events->pluck('id')->all(),
            $team->events()->get()->pluck('id')->all()
        );
    }

    #[Test]
    public function a_team_cannot_participate_in_same_event_again()
    {
        $users = create(User::class, 2);
        $team = $users[0]->createTeam('EK or EK GYARAH', $users[1]);
        $event = create(Event::class);
        $team->participate($event);

        $this->withoutExceptionHandling()->be($users[0]);

        $this->assertCount(1, $team->events()->get());

        $this->post(route('events.participate', $event), [
            'team_id' => $team->id,
        ]);

        $this->assertCount(1, $team->events()->get());
        $this->assertEquals($event->id, $team->events()->first()->id);
    }

    #[Test]
    public function a_team_cannot_participate_in_a_event_if_any_of_its_member_is_already_participating()
    {
        $users = create(User::class, 3);
        $team1 = $users[0]->createTeam('EK or EK GYARAH', $users[1]);
        $team2 = $users[0]->createTeam('DO DUNI CHAR', $users[2]);
        $event = create(Event::class);

        $team1->participate($event);

        $this->withoutExceptionHandling()->be($users[0]);

        $this->assertCount(0, $team2->events()->get());

        $this->post(route('events.participate', $event), [
            'team_id' => $team2->id,
        ])->assertSessionHas('flash_notification');

        $this->assertEquals('danger', \Session::get('flash_notification')->first()->level);
        $this->assertCount(0, $team2->events()->get());
    }

    #[Test]
    public function user_cannot_participate_as_team_of_which_he_is_not_a_member()
    {
        $users = create(User::class, 2);

        $this->be($currentUser = create(User::class));

        $event = create(Event::class);
        $otherTeam = $users[0]->createTeam('2 Person Team', $users[1]->id);
        $userTeam = $currentUser->createTeam('My Team');

        $this->post(route('events.participate', $event), [
            'team_id' => $otherTeam->id,
        ])->assertRedirect()
            ->assertSessionHasErrors('team_id');

        $this->assertCount(0, $otherTeam->events()->get());
    }
}
