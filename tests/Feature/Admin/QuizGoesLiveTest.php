<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizGoesLiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function quiz_is_set_active_in_the_event()
    {
        $events = create(Event::class, 2, ['started_at' => null]);
        $quizzes = create(Quiz::class, 2, ['event_id' => $events[0]->id]);

        $this->withoutExceptionHandling()->signInAdmin();

        $this->assertFalse($quizzes[0]->fresh()->isActive);

        $response = $this->postJson(route('admin.quizzes.go-live', $quizzes[0]))
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('quiz', $response);
        $this->assertEquals($quizzes[0]->fresh()->opened_at->toJson(), $response['quiz']['opened_at']);
        $this->assertTrue($response['quiz']['isActive']);
        $this->assertFalse($response['quiz']['isClosed']);
        $this->assertArrayHasKey('event', $response['quiz']);
        $this->assertTrue($response['quiz']['event']['isLive']);

        $this->assertInstanceOf(Carbon::class, $quizzes[0]->fresh()->opened_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $quizzes[0]->fresh()->opened_at->getTimestamp(), 2);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $quizzes[0]->fresh()->event->started_at->getTimestamp(), 2);
        $this->assertEquals($quizzes[0]->id, $events[0]->fresh()->active_quiz_id);
    }
}
