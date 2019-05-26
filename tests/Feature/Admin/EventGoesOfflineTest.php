<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use Carbon\Carbon;

class EventGoesOfflineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_can_be_set_offline_by_admin()
    {
        $events = create(Event::class, 4);

        $this->withoutExceptionHandling()->signInAdmin();

        $events[0]->setLive();

        $json = $this->postJson(route('admin.events.end', $events[0]))
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('status', $json);
        $this->assertArrayHasKey('event', $json);
        $this->assertArrayHasKey('id', $json['event']);
        $this->assertEquals('success', $json['status']);
        $this->assertArrayHasKey('message', $json);
        $this->assertFalse($events[0]->fresh()->isLive);
        $this->assertInstanceOf(Carbon::class, $events[0]->fresh()->ended_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $events[0]->fresh()->ended_at->getTimestamp(), 2);
    }
}
