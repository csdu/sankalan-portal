<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Team;
use App\User;

class ViewTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_Teams()
    {
        $teams = create(Team::class, 5);
        $this->withoutExceptionHandling()->signInAdmin();

        $resultTeams = $this->get(route('admin.teams.index'))->viewData('teams');

        $this->assertCount(5, $resultTeams);
    }
}
