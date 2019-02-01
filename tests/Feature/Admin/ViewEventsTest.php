<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\User;

class ViewEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_events()
    {
        $events = create(Event::class, 5);
        $this->withoutExceptionHandling()->signInAdmin();

        $resultEvents = $this->get(route('events.index'))->viewData('events');

        $this->assertCount(5, $resultEvents);
    }
}
