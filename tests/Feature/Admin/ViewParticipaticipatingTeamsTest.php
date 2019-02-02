<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Collection;
use App\Team;
use App\Event;

class ViewParticipaticipatingTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_lists_all_particpating_teams()
    {
        $events = create(Event::class, 12);
        $teams = create(Team::class, 10);

        $teams->each(function($team) use ($events){
            $events->random(4)->each(function($event) use($team) {
                $team->participate($event);
            });
        });

        $this->withoutExceptionHandling()->signInAdmin();

        $results = $this->get(route('participations.index'))
            ->assertSuccessful()
            ->viewData('participations');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(40, $results);
        $this->assertArrayHasKey('team', $results->first()->toArray());
        $this->assertInstanceOf(Team::class, $results->first()->team);
        $this->assertArrayHasKey('event', $results->first()->toArray());
        $this->assertInstanceOf(Event::class, $results->first()->event);
    }

    /** @test */
    public function admin_lists_all_particpating_teams_filtered_by_events()
    {
        $events = create(Event::class, 3);
        $teams = create(Team::class, 10);

        $teams->random(5)->each(function ($team) use ($events) {
            $team->participate($events[0]);
        });

        $teams->random(5)->each(function ($team) use ($events) {
            $team->participate($events[1]);
        });

        $this->withoutExceptionHandling()->be(create(User::class, 1, ['is_admin' => true]));

        $results = $this->get(route('participations.index') . '?' . http_build_query(['event' => $events[0]->id]) )
            ->assertSuccessful()
            ->viewData('participations');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertEquals($events[0]->teams()->count(), $results->count());
    }

    /** @test */
    public function admin_lists_all_particpating_teams_when_event_query_is_empty_string()
    {
        $events = create(Event::class, 3);
        $teams = create(Team::class, 10);

        $teams->random(5)->each(function ($team) use ($events) {
            $team->participate($events[0]);
        });

        $teams->random(5)->each(function ($team) use ($events) {
            $team->participate($events[1]);
        });

        $this->withoutExceptionHandling()->be(create(User::class, 1, ['is_admin' => true]));

        $results = $this->get(route('participations.index') . '?' . http_build_query(['event' => '']))
            ->assertSuccessful()
            ->viewData('participations');

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertEquals(10, $results->count());
    }
}
