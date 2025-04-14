<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizGoesOfflineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_closes_quiz_with_a_closed_at_timestamp(): void
    {
        $quiz = create(Quiz::class);
        $quiz->setActive();

        $this->withoutExceptionHandling()
            ->signInAdmin()
            ->postJson(route('admin.quizzes.close', $quiz))
            ->assertRedirect();

        $this->assertFalse($quiz->fresh()->isActive, 'quiz is still active after being closed.');
        $this->assertInstanceOf(Carbon::class, $quiz->fresh()->closed_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $quiz->fresh()->closed_at->getTimestamp(), 1);
    }
}
