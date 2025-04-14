<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ViewEventsTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_lists_all_particpating_teams()
    {
        $events = create(Event::class, 5);
        $teams = create(Team::class, 20);

        $teams->each(
            fn ($team) => $events->random(2)->each(
                fn ($event) => $team->participate($event)
            )
        );

        $this->withoutExceptionHandling()->signInAdmin();

        $response = $this->get(route('admin.events.teams.index'))
            ->assertSuccessful();

        $results = $response->viewData('events_teams');
        $viewEvents = $response->viewData('events');

        $this->assertCount(5, $viewEvents);
        $this->assertArrayHasKey('slug', $viewEvents->first()->toArray());
        $this->assertArrayHasKey('title', $viewEvents->first()->toArray());

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertCount(config('app.pagination.perPage'), $results->toArray()['data']);
        $this->assertEquals(40, $results->toArray()['total']);
        collect($results->toArray()['data'])->each(function ($result) {
            $this->assertArrayHasKey('event', $result);
            $this->assertArrayHasKey('team', $result);
            $this->assertArrayHasKey('members', $result['team']);
        });
    }

    /** @test */
    public function admin_lists_all_particpating_teams_filtered_by_events()
    {
        $events = create(Event::class, 3);
        $teams = create(Team::class, 3);

        $events->each(
            fn ($event) => $teams->random(2)->each(
                fn ($team) => $team->participate($event)
            )
        );

        $this->withoutExceptionHandling()->be(create(User::class, 1, ['is_admin' => true]));

        $response = $this->get(route('admin.events.teams.index', $events[0]))
            ->assertSuccessful();
        $results = $response->viewData('events_teams');
        $viewEvents = $response->viewData('events');

        $this->assertCount(3, $viewEvents);

        $this->assertInstanceOf(LengthAwarePaginator::class, $results);
        $this->assertEquals(2, $results->total());
        collect($results->toArray()['data'])->each(function ($result) use ($events) {
            $this->assertEquals($events[0]->id, $result['event_id']);
        });
    }
}
