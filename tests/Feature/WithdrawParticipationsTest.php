<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\User;

class WithdrawParticipationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_withdraw_individual_participation_from_an_event()
    {
        $user = factory(User::class)->create();
        $events = factory(Event::class, 4)->create();
        $team = $user->createTeam('My Team');
        $team->participate($events[0]);

        $this->withoutExceptionHandling()->be($user);

        $this->delete(route('events.withdraw-part', $events[0]))
            ->assertRedirect()
            ->assertSessionHas('flash_notification');

        $this->assertCount(0, $team->events()->get());
        $this->assertCount(0, $events[0]->teams()->get());

        $this->assertEquals(
            'success', 
            \Session::get('flash_notification')->first()->level
        );
    }

    /** @test */
    public function user_can_withdraw_group_participation_from_an_event()
    {
        $users = factory(User::class, 2)->create();
        $events = factory(Event::class, 4)->create();
        $team = $users[0]->createTeam('My Team', $users[1]);
        $team->participate($events[0]);
        $team->participate($events[1]);

        $this->withoutExceptionHandling()->be($users[0]);

        $this->delete(route('events.withdraw-part', $events[0]))
            ->assertRedirect()
            ->assertSessionHas('flash_notification');

        $this->assertCount(1, $team->events()->get());
        $this->assertCount(0, $events[0]->teams()->get());
        $this->assertCount(1, $events[1]->teams()->get());

        $this->assertEquals(
            'success',
            \Session::get('flash_notification')->first()->level
        );
    }
}
