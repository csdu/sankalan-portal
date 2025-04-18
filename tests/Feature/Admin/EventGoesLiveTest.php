<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventGoesLiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_can_be_set_live_by_admin()
    {
        $events = create(Event::class, 4);

        $this->withoutExceptionHandling()->signInAdmin();

        $this->postJson(route('admin.events.go-live', $events[0]))
            ->assertRedirect();

        $this->assertTrue($events[0]->fresh()->isLive);
        $this->assertInstanceOf(Carbon::class, $events[0]->fresh()->started_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $events[0]->fresh()->started_at->getTimestamp(), 2);
    }
}
