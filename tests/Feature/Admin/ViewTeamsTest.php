<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Team;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ViewTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_Teams()
    {
        $teams = create(Team::class, 25);
        $this->withoutExceptionHandling()->signInAdmin();

        $resultTeams = $this->get(route('admin.teams.index'))->viewData('teams');

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultTeams);
        $this->assertCount(15, $resultTeams);
        $this->assertEquals(25, $resultTeams->total());
        $this->assertArrayHasKey('members', $resultTeams->first()->toArray());
        $this->assertArrayHasKey('events_count', $resultTeams->first()->toArray());
    }
}
