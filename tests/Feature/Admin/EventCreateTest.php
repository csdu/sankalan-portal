<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_sees_create_form()
    {
        $this->withoutExceptionHandling()->signInAdmin();
        $res = $this->get(route('admin.events.create'));
        $res->assertStatus(200);
    }

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

        $this->post(route('admin.events.store'), $data);
        $this->assertDatabaseHas('Events', $data);
    }
}
