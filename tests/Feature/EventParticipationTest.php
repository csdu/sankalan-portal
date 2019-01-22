<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\Team;
use App\User;

class EventParticipationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     *
     * @return void
     */
    public function user_can_participate_in_any_event_as_a_team()
    {
        $event = factory(Event::class)->create();
        $team = factory(Team::class)->create();
        $users = factory(User::class, 2)->create();
        
        $team->members()->sync($users->pluck('id')->toArray());

        $this->withoutExceptionHandling()->be($users[0]);
        
        $this->assertCount(0, $event->fresh()->teams);

        $this->post(route('events.participate', $event), [
            'team_id' => $team->id
        ]);
        
        tap($event->fresh()->teams, function($participatingTeams) use ($team){
            $this->assertCount(1, $participatingTeams);
            $this->assertEquals($participatingTeams->first()->id, $team->id);
        });
    }

    /**
     * @test
     *
     * @return void
     */
    public function user_can_participate_in_any_event_as_an_individual()
    {
        $event = factory(Event::class)->create();
        $user = factory(User::class)->create();

        $this->withoutExceptionHandling()->be($user);

        $this->assertCount(0, $event->fresh()->users);

        $this->post(route('events.participate', $event));

        tap($event->fresh()->users, function ($participatingUsers) use ($user) {
            $this->assertCount(1, $participatingUsers);
            $this->assertEquals($participatingUsers->first()->id, $user->id);
        });
    }
}
