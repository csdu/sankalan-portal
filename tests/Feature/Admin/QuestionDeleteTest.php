<?php

namespace Tests\Feature\Admin;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuestionDeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_delete_question_if_it_quiz_is_not_active()
    {
        $quiz = create(Quiz::class);
        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
        ]);

        $this->signInAdmin();

        $res = $this->delete(route('admin.quizzes.questions.delete', [$quiz, $question]));
        $this->assertDatabaseMissing('quizzes', $question->getAttributes());
        $res->assertRedirect(route('admin.quizzes.show', $quiz));
    }

    #[Test]
    public function admin_can_not_delete_quiz_if_it_is_active()
    {
        $quiz = create(Quiz::class);
        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
        ]);

        $this->signInAdmin();

        $quiz->setActive();

        $response = $this->delete(route('admin.quizzes.questions.delete', [$quiz, $question]));
        $response->assertStatus(403);
    }
}
