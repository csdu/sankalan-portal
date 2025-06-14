<?php

namespace Tests\Feature\Admin;

use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuizDeleteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_delete_quiz_if_it_is_not_active()
    {
        $quiz = create(Quiz::class, 1);
        $this->signInAdmin();

        $res = $this->delete(route('admin.quizzes.delete', $quiz));
        $this->assertDatabaseMissing('quizzes', $quiz->getAttributes());
        $res->assertRedirect(route('admin.quizzes.index'));
    }

    #[Test]
    public function admin_can_not_delete_quiz_if_it_is_active()
    {
        $quiz = create(Quiz::class, 1);
        $this->signInAdmin();

        $quiz->setActive();

        $response = $this->delete(route('admin.quizzes.delete', $quiz));
        $response->assertStatus(403);
    }
}
