<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Quiz;
use App\Question;
use App\QuizParticipation;


class ViewAllQuizzesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_can_view_all_quizzes_with_event_questions_participations_count()
    {
        $quizzes = create(Quiz::class, 10);
        $quizzes->each(function($quiz) {
            create(QuizParticipation::class, 5, ['quiz_id' => $quiz->id]);
            create(Question::class, 10, ['quiz_id' => $quiz->id]);
        });

        $viewQuizzes = $this->signInAdmin()
            ->withoutExceptionHandling()
            ->get(route('admin.quizzes.index'))
            ->assertSuccessful()
            ->assertViewIs('admin.quizzes.index')
            ->viewData('quizzes');

        $this->assertCount(10, $viewQuizzes);

        $viewQuizzes->each(function ($quiz) {
            $this->assertArrayHasKey('event', $quiz->toArray());
            $this->assertArrayHasKey('participations_count', $quiz->toArray());
            $this->assertArrayHasKey('questions_count', $quiz->toArray());
        });

    }
}
