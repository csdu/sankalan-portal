<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Team;
use App\User;

class CreateTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_create_individual_team()
    {
        $this->withoutExceptionHandling();
        $this->be($user = factory(User::class)->create());

        $this->assertCount(0, $teams = Team::all());

        $name = 'Team Name';
        $this->post(route('teams.store'), [
            'name' => $name,
        ]);

        $this->assertCount(1, $teams = Team::all());
        $this->assertEquals(1, $teams->first()->id);
        $this->assertCount(1, $teams->first()->members);
        $this->assertEquals($name, $teams->first()->name);

        $this->assertInstanceOf(Team::class, $user->fresh()->individualTeam);
        $this->assertEquals(1, $user->fresh()->individualTeam->id);

    }

    /**
     * @test
     */
    public function user_can_create_two_members_team()
    {
        $this->withoutExceptionHandling();
        $this->be($user = factory(User::class)->create());
        $anotherUser = factory(User::class)->create();

        $this->assertCount(0, $teams = Team::all());

        $name = 'Team Name';
        $this->post(route('teams.store'), [
            'name' => $name,
            'member_id' => $anotherUser->id
        ]);

        $this->assertCount(1, $teams = Team::all());
        tap($teams->first(), function($team) use ($name, $user, $anotherUser) {
            $this->assertEquals(1, $team->id);
            $this->assertCount(2, $team->members);
            $this->assertArraySubset(
                [$user->id, $anotherUser->id], 
                $team->members->pluck('id')
            );
            $this->assertEquals($name, $team->name);
        });
        $this->assertNull($user->individualTeam);


    }
}
