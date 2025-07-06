<?php

namespace Tests\Unit;

use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuizResponseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::now());
    }

    protected function tearDown(): void
    {
        Date::setTestNow();
        parent::tearDown();
    }

    public function testTimeLeftCorrectlyEvaluatesExtraTime()
    {
        $quiz = create(Quiz::class, 1, ['time_limit' => 300]); // 5 mins
        $quizResponse = create(QuizResponse::class, 1, [
            'quiz_id' => $quiz->id,
            'started_at' => Date::now()
        ]);

        $this->assertEqualsWithDelta(300, $quizResponse->timeLeft, 2);

        $newStartTime = Date::now()->addMinutes(2);
        $quizResponse->update(['started_at' => $newStartTime]);

        $quizResponse->refresh();

        $this->assertEqualsWithDelta(
            $newStartTime->getTimestamp(),
            $quizResponse->started_at->getTimestamp(),
            2,
            'Started at time should be exactly 2 minutes in the future'
        );

        // Since we moved the start time 2 minutes into the future,
        // we should have the original 5 minutes (300 seconds) plus 2 minutes (120 seconds)
        $this->assertEqualsWithDelta(420, $quizResponse->timeLeft, 2);
    }
}
