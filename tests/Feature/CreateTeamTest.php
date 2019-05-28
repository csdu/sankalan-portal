<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_create_individual_team()
    {
        $this->withoutExceptionHandling();
        $this->be($user = create(User::class));

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
        $this->be($user = create(User::class));
        $anotherUser = create(User::class);

        $this->assertCount(0, $teams = Team::all());

        $name = 'Team Name';
        $this->post(route('teams.store'), [
            'name' => $name,
            'member_email' => $anotherUser->email,
        ]);

        $this->assertCount(1, $teams = Team::all());
        tap($teams->first(), function ($team) use ($name, $user, $anotherUser) {
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

    /** @test */
    public function user_can_not_create_team_with_himself()
    {
        $this->withExceptionHandling();
        $this->be($user = create(User::class));

        $this->assertCount(0, Team::all());

        $name = 'Team Name';
        $response = $this->post(route('teams.store'), [
            'name' => $name,
            'member_email' => $user->email,
        ]);

        $this->assertCount(0, Team::all());
        $this->assertNull($user->individualTeam);

        $response->assertRedirect()->assertSessionHasErrors('member_email');
        $this->assertContains('cannot teamup with yourself', \Session::get('errors')->first());
    }

    /**
     * @test
     */
    public function user_can_not_create_team_with_more_than_two_members()
    {
        $this->withExceptionHandling();
        $this->be($user = create(User::class));
        $otherUser = create(User::class);
        $anotherUser = create(User::class);

        $this->assertCount(0, $teams = Team::all());

        $response = $this->post(route('teams.store'), [
            'name' => 'Team Name',
            'member_email' => [$anotherUser->email, $otherUser->email],
        ]);

        $response->assertRedirect()->assertSessionHasErrors('member_email');

        // No Team is Created!
        $this->assertCount(0, $teams = Team::all());
        $this->assertNull($user->individualTeam);
    }

    /**
     * @test
     */
    public function user_can_create_single_member_team_with_empty_member_email()
    {
        $this->withExceptionHandling();
        $this->be($user = create(User::class));
        $otherUser = create(User::class);
        $anotherUser = create(User::class);

        $this->assertCount(0, $teams = Team::all());

        $response = $this->post(route('teams.store'), [
            'name' => 'Team Name',
            'member_email' => '',
        ]);

        // Individual Team is Created!
        $this->assertCount(1, $teams = Team::all());
        $this->assertInstanceOf(Team::class, $user->individualTeam);
    }

    /**
     * @test
     */
    public function user_cannot_create_single_member_team_if_already_exists()
    {
        $user = create(User::class);
        $otherUser = create(User::class);
        $team = $user->createTeam('My Team');
        $groupTeam = $user->createTeam('Group Team', $otherUser);

        $this->withoutExceptionHandling()
            ->be($user);

        // assert User has individual Team Before
        $this->assertCount(2, $user->teams()->get());
        $this->assertInstanceOf(Team::class, $user->individualTeam);

        // try creating another individual team
        $response = $this->post(route('teams.store'), [
            'name' => 'Team Name',
            'member_email' => '',
        ]);

        $response->assertRedirect();

        // Another Individual Team is not Created!
        $this->assertCount(2, $user->teams()->get());
        $this->assertInstanceOf(Team::class, $user->fresh()->individualTeam);
        $this->assertEquals($team->id, $user->fresh()->individualTeam->id);

        // try creating another team with same member
        $response = $this->post(route('teams.store'), [
            'name' => 'Team Name',
            'member_email' => $otherUser->email,
        ]);

        $response->assertRedirect();

        // Another Team is not Created!
        $this->assertCount(2, $user->teams()->get());
    }
}
