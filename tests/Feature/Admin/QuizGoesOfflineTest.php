<?php

namespace Tests\Feature\Admin;

use App\Quiz;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizGoesOfflineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_closes_quiz_with_a_closed_at_timestamp()
    {
        $quiz = create(Quiz::class);
        $quiz->setActive();

        $response = $this->withoutExceptionHandling()
            ->signInAdmin()
            ->postJson(route('admin.quizzes.close', $quiz))
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('quiz', $response);
        $this->assertArrayHasKey('event', $response['quiz']);
        $this->assertEquals($quiz->fresh()->opened_at->toDateTimeString(), $response['quiz']['opened_at']);
        $this->assertFalse($response['quiz']['isActive']);
        $this->assertTrue($response['quiz']['isClosed']);

        $this->assertFalse($quiz->fresh()->isActive, 'quiz is still active after being closed.');
        $this->assertInstanceOf(Carbon::class, $quiz->fresh()->closed_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $quiz->fresh()->closed_at->getTimestamp(), 1);
    }
}
