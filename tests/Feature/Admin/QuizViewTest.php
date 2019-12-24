<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_see_questions_of_a_quiz()
    {
        $quiz = create(Quiz::class);

        $this->signInAdmin();

        $res = $this->get(route('admin.quizzes.show', $quiz));
        $res->assertSuccessful();
    }

    /** @test */
    public function normal_user_can_not_see_questions_of_a_quiz()
    {
        $quiz = create(Quiz::class);

        $res = $this->get(route('admin.quizzes.show', $quiz));
        $res->assertRedirect();
    }
}
