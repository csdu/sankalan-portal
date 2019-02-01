<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\Quiz;
use App\QuizParticipation;

class ViewQuizParticipationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_quiz_participations()
    {
        $quiz = create(Quiz::class);
        create(QuizParticipation::class, 5, ['quiz_id' => $quiz->id]);

        $viewParticipations = $this->signInAdmin()
            ->withoutExceptionHandling()
            ->get(route('quizzes.participations.index', $quiz))
            ->assertSuccessful()
            ->assertViewIs('admin.quiz-participations.index')
            ->viewData('participations');

        $this->assertCount(5, $viewParticipations);

        tap($viewParticipations->first(), function($firstParticipation) {
            $this->assertArrayHasKey('quiz', $firstParticipation->toArray());
            $this->assertArrayHasKey('team', $firstParticipation->toArray());
            $this->assertArrayHasKey('responses', $firstParticipation->toArray());
            $this->assertCount(0, $firstParticipation->responses);
        });

    }
}
