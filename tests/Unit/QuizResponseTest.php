<?php

namespace Tests\Unit;

use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizResponseTest extends TestCase
{
    use RefreshDatabase;

    public function testTimeLeftCorrectlyEvaluatesExtraTime()
    {
        $quiz = create(Quiz::class, 1, ['time_limit' => 300]); // 5 mins
        $quizResponse = create(QuizResponse::class, 1, [
            'quiz_id' => $quiz->id,
            'started_at' => now(),
        ]);

        $this->assertEqualsWithDelta(300, $quizResponse->timeLeft, 2);

        $quizResponse->update(['started_at' => now()->addMinutes(2)]);

        $this->assertEquals(now()->addMinutes(2)->format('Y-m-d H:i:s'), $quizResponse->fresh()->started_at->format('Y-m-d H:i:s'));
        $this->assertEqualsWithDelta(420, $quizResponse->fresh()->timeLeft, 2);
    }
}
