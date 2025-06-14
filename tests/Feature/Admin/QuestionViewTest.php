<?php

namespace Tests\Feature\Admin;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuestionViewTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_see_single_question_and_options_of_a_quiz()
    {
        $quiz = create(Quiz::class);
        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
        ]);

        $option = create(QuestionOption::class, 1, [
            'question_id' => $question->id,
        ]);

        $this->signInAdmin();

        $res = $this->get(route('admin.quizzes.questions.show', [$quiz, $question]));
        $res->assertSuccessful();
        $res->assertSee($question->text);
        $res->assertSee($option->text);
    }

    #[Test]
    public function normal_user_can_not_see_questions_of_a_quiz()
    {
        $quiz = create(Quiz::class);
        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
        ]);

        $res = $this->get(route('admin.quizzes.questions.show', [$quiz, $question]));
        $res->assertRedirect();
    }
}
