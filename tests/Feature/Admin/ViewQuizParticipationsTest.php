<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Quiz;
use App\Team;
use Illuminate\Pagination\LengthAwarePaginator;

class ViewQuizParticipationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_quiz_participations()
    {
        $quizzes = create(Quiz::class, 4);
        $teams = create(Team::class, 12);

        $teams->random(8)->each(function ($team) use ($quizzes) {
            $quizzes->each(function ($quiz) use ($team) {
                $team->participate($quiz->event);
                $quiz->setActive();
                $quiz->allowTeam($team);
            });
        });

        $response = $this->signInAdmin()
            ->withoutExceptionHandling()
            ->get(route('admin.quizzes.teams.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.quizzes_teams.index');

        $viewParticipations = $response->viewData('quizzes_teams');
        $quiz = $response->viewData('quiz');
        $quizzes = $response->viewData('quizzes');

        $this->assertCount(4, $quizzes);
        $this->assertInstanceOf(LengthAwarePaginator::class, $viewParticipations);
        $this->assertCount(config('app.pagination.perPage'), $viewParticipations);
        $this->assertEquals(32, $viewParticipations->total());
        $this->assertNull($quiz);

        tap($viewParticipations->first(), function ($firstParticipation) {
            $this->assertArrayHasKey('quiz', $firstParticipation->toArray());
            $this->assertArrayHasKey('team', $firstParticipation->toArray());
            $this->assertArrayHasKey('members', $firstParticipation->toArray()['team']);
            $this->assertArrayHasKey('questions_count', $firstParticipation->toArray()['quiz']);
            $this->assertArrayHasKey('responses_count', $firstParticipation->toArray());
        });
    }

    /** @test */
    public function admin_can_view_specific_quiz_participations()
    {
        $quizzes = create(Quiz::class, 4);
        $teams = create(Team::class, 4);

        $quizzes->each(function ($quiz, $index) use ($teams) {
            $teams->random($index + 1)->each(function ($team) use ($quiz) {
                $team->participate($quiz->event);
                $quiz->setActive();
                $quiz->allowTeam($team);
            });
        });

        $response = $this->signInAdmin()
            ->withoutExceptionHandling()
            ->get(route('admin.quizzes.teams.index', $quizzes[2]))
            ->assertSuccessful()
            ->assertViewIs('admin.quizzes_teams.index');

        $viewParticipations = $response->viewData('quizzes_teams');
        $quiz = $response->viewData('quiz');
        $quizzes = $response->viewData('quizzes');

        $this->assertCount(4, $quizzes);
        $this->assertCount(3, $viewParticipations);
        $this->assertNotNull($quiz);
    }
}
