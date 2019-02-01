<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\User;

class EventGoesLiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_can_be_set_live_by_admin()
    {
        $events = create(Event::class, 4);
        $admin = create(User::class, 1, ['is_admin' => true]);

        $this->withoutExceptionHandling()->be($admin);

        $json = $this->postJson(route('events.go-live', $events[0]))
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('status', $json);
        $this->assertEquals('success', $json['status']);
        $this->assertArrayHasKey('message', $json);
        $this->assertTrue($events[0]->fresh()->isLive());
    }
}
