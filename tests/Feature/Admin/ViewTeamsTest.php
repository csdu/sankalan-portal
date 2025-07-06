<?php

namespace Tests\Feature\Admin;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ViewTeamsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function list_all_Teams()
    {
        $teams = create(Team::class, 25);
        $this->withoutExceptionHandling()->signInAdmin();

        $resultTeams = $this->get(route('admin.teams.index'))->viewData('teams');

        $this->assertInstanceOf(LengthAwarePaginator::class, $resultTeams);
        $this->assertCount(config('app.pagination.perPage'), $resultTeams);
        $this->assertEquals(25, $resultTeams->total());
        $this->assertArrayHasKey('members', $resultTeams->first()->toArray());
        $this->assertArrayHasKey('events_count', $resultTeams->first()->toArray());
    }
}
