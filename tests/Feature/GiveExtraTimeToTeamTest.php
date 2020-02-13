<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizResponse;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiveExtraTimeToTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_give_extra_time_to_team()
    {
        $quiz = create(Quiz::class, 1, ['time_limit' => 300]); // 5 mins
        $quizResponse = create(QuizResponse::class, 1, ['quiz_id' => $quiz->id]);

        $this->signInAdmin();

        $res = $this->post(route('admin.quizzes.teams.extra-time', $quizResponse), [
            'time' => 5, //minutes
        ])->assertRedirect();
        $time = now()->addMinutes(5);

        $this->assertEqualsWithDelta($time, $quizResponse->fresh()->started_at, 1);
        $this->assertEqualsWithDelta(600, $quizResponse->fresh()->timeLeft, 2);
    }

    /** @test */
    public function admin_can_give_extra_time_to_team_after_they_end_quiz()
    {
        $quiz = create(Quiz::class, 1, ['time_limit' => 300]); // 5 mins
        $quizResponse = create(QuizResponse::class, 1, ['quiz_id' => $quiz->id]);

        $this->signInAdmin();

        Carbon::setTestNow(now()->addMinutes(4));

        $quizResponse->team->endQuiz($quiz);
        $this->assertEquals(60, $quizResponse->fresh()->timeLeft);

        $res = $this->post(route('admin.quizzes.teams.extra-time', $quizResponse), [
            'time' => 5, //minutes
        ])->assertRedirect();

        $this->assertEquals(Carbon::getTestNow()->addMinutes(5)->format('Y-m-d H:i:s'), $quizResponse->fresh()->started_at->format('Y-m-d H:i:s'));
        $this->assertEqualsWithDelta(300, $quizResponse->fresh()->timeLeft, 2);
    }
}
