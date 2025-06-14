<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GiveExtraTimeToTeamTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_give_extra_time_to_team()
    {
        $quiz = create(Quiz::class, 1, ['time_limit' => 300]); // 5 mins
        $quizResponse = create(QuizResponse::class, 1, ['quiz_id' => $quiz->id]);

        $this->signInAdmin();

        $this->post(route('admin.quizzes.teams.extra-time', $quizResponse), [
            'time' => 5, //minutes
        ])->assertRedirect();

        $expectedStartTime = Date::now()->addMinutes(5);
        $quizResponse->refresh();

        $this->assertEqualsWithDelta(
            $expectedStartTime->getTimestamp(),
            $quizResponse->started_at->getTimestamp(),
            2,
            'Started at time should be exactly 5 minutes in the future'
        );

        // Original time limit (300 seconds) + 5 minutes extra (300 seconds) = 600 seconds
        $this->assertEqualsWithDelta(600, $quizResponse->timeLeft, 2);
    }

    #[Test]
    public function admin_can_give_extra_time_to_team_after_they_end_quiz()
    {
        $quiz = create(Quiz::class, 1, ['time_limit' => 300]); // 5 mins
        $quizResponse = create(QuizResponse::class, 1, ['quiz_id' => $quiz->id]);

        $this->signInAdmin();

        Date::setTestNow(Date::now()->addMinutes(4));
        $quizResponse->team->endQuiz($quiz);
        $this->assertEqualsWithDelta(60, $quizResponse->fresh()->timeLeft, 5);

        $this->post(route('admin.quizzes.teams.extra-time', $quizResponse), [
            'time' => 5, //minutes
        ])->assertRedirect();

        $expectedStartTime = Date::now()->addMinutes(5);
        $quizResponse->refresh();

        $this->assertEqualsWithDelta(
            $expectedStartTime->getTimestamp(),
            $quizResponse->started_at->getTimestamp(),
            2,
            'Started at time should be exactly 5 minutes from test time'
        );

        $this->assertEqualsWithDelta(600, $quizResponse->timeLeft, 2);
    }
}
