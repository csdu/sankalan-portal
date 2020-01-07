<?php

namespace Tests\Feature;

use App\Models\QuizResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GiveExtraTimeToTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_give_extra_time_to_team()
    {
        $quizResponse = create(QuizResponse::class);

        $this->signInAdmin();

        $res = $this->post(route('admin.quizzes.teams.extra-time', $quizResponse), [
            'time' => 5, //minutes
        ]);
        $time = $quizResponse->started_at->add(5, 'minutes');

        $res->assertRedirect(route('admin.quizzes.teams.index'));

        $res = $this->get(route('admin.quizzes.teams.index'));
        $res->assertSee($time);
    }
}
