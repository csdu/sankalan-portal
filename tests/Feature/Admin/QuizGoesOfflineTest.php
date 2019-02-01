<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use App\Quiz;

class QuizGoesOfflineTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_closes_quiz_with_a_closed_at_timestamp()
    {
        $quiz = create(Quiz::class);
        $quiz->setActive();

        $this->withoutExceptionHandling()
            ->signInAdmin()
            ->post(route('quizzes.close', $quiz))
            ->assertSuccessful();

        $this->assertFalse($quiz->fresh()->isActive(), 'quiz is still active after being closed.');
        $this->assertInstanceOf(Carbon::class, $quiz->fresh()->closed_at);
        $this->assertEqualsWithDelta(now()->getTimestamp(), $quiz->fresh()->closed_at->getTimestamp(), 1);

    }
}
