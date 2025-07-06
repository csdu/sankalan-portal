<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Illuminate\Support\Facades\Date;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventGoesOfflineTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function event_can_be_set_offline_by_admin()
    {
        $events = create(Event::class, 4);

        $this->withoutExceptionHandling()->signInAdmin();

        $events[0]->setLive();

        $this->postJson(route('admin.events.end', $events[0]))
            ->assertRedirect();

        $this->assertFalse($events[0]->fresh()->isLive);
        $this->assertInstanceOf(\DateTimeInterface::class, $events[0]->fresh()->ended_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $events[0]->fresh()->ended_at->getTimestamp(), 2);
    }
}
