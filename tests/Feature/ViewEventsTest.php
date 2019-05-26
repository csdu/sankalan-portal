<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\Quiz;

class ViewEventsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function list_all_events()
    {
        $events = create(Event::class, 5);
        $quiz = create(Quiz::class, 1, ['event_id' => $events[0]->id]);
        $quiz->setActive();
        $this->withoutExceptionHandling()->signIn();

        $resultEvents = $this->get(route('events.index'))
            ->assertViewIs('events.index')
            ->viewData('events');

        $this->assertCount(5, $resultEvents);
        tap($resultEvents->first()->toArray(), function ($event) use ($quiz) {
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('isLive', $event);
            $this->assertArrayHasKey('hasEnded', $event);
            $this->assertArrayHasKey('teams', $event);
            $this->assertArrayHasKey('active_quiz', $event);
            $this->assertEquals($quiz->id, $event['active_quiz']['id']);
        });
    }
}
