<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_an_event()
    {
        $this->withoutExceptionHandling()->signInAdmin();

        $data = [
            'title' => 'event title',
            'slug' => 'event-title',
            'description' => 'event description',
            'rounds' => 2,
        ];

        Event::create($data);

        $this->assertDatabaseHas('Events', $data);
    }
}
