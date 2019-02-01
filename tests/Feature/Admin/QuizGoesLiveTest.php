<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Event;
use App\Quiz;
use Carbon\Carbon;

class QuizGoesLiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function quiz_is_set_active_in_the_event()
    {
        $events = create(Event::class, 2, ['is_live' => true]);
        $quizzes = create(Quiz::class, 2, ['event_id' => $events[0]->id]);

        $this->withoutExceptionHandling()->signInAdmin();

        $this->assertFalse($quizzes[0]->fresh()->isActive());

        $response = $this->postJson(route('quizzes.go-live', $quizzes[0]))
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertTrue($quizzes[0]->fresh()->isActive());
        $this->assertInstanceOf(Carbon::class, $quizzes[0]->fresh()->opened_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $quizzes[0]->fresh()->opened_at->getTimestamp(), 2);
        $this->assertEquals($quizzes[0]->id, $events[0]->fresh()->active_quiz_id);
    }
}
