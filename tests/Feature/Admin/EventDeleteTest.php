<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventDeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_delete_event_if_it_has_not_started()
    {
        $event = create(Event::class, 1);
        $this->withoutExceptionHandling()->signInAdmin();

        $this->delete(route('admin.events.delete', $event));

        $this->assertDatabaseMissing('events', [
            'id' => $event->id
        ]);
    }

    #[Test]
    public function admin_can_not_delete_event_if_it_has_started()
    {
        $event = create(Event::class, 1, [
            'started_at' => now(),
        ]);
        $this->signInAdmin();

        $response = $this->delete(route('admin.events.delete', $event));
        $response->assertStatus(403);
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }
}
