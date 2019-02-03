<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\User;
use App\Quiz;

class ViewEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_events()
    {
        $events = create(Event::class, 5);
        create(Quiz::class, 1, ['event_id' => $events[0]->id])->setActive();
        $this->withoutExceptionHandling()->signInAdmin();

        $resultEvents = $this->get(route('admin.events.index'))->viewData('events');

        $this->assertCount(5, $resultEvents);
        tap($resultEvents->first()->toArray(), function($event) {
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('isLive', $event);
            $this->assertArrayHasKey('hasEnded', $event);
            $this->assertArrayHasKey('quizzes_count', $event);
            $this->assertArrayHasKey('teams_count', $event);
            $this->assertArrayHasKey('active_quiz', $event);
            $this->assertArrayHasKey('id', $event);
        });
    }
}
