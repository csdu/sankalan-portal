<?php

namespace Tests\Feature\Admin;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_sees_create_form()
    {
        $quiz = create(Quiz::class);

        $this->signInAdmin();
        $res = $this->get(route('admin.quizzes.questions.create', $quiz));
        $res->assertOk();
    }

    /** @test */
    public function admin_can_create_a_question()
    {
        $quiz = create(Quiz::class);
        $this->signInAdmin();

        $data = [
            'qno' => 1,
            'positive_score' => 4,
            'negative_score' => 1,
            'text' => '# test question',
            'compiledHTML' => '<h1>test question</h1>',
            'type' => 'mcq',
            'correct_answer_keys' => 'nokey',
        ];

        $res = $this->post(route('admin.quizzes.questions.store', $quiz), $data);
        $res->assertCreated();

        // options
        $question = create(Question::class);

        $option = [
            'key' => str_random(3),
            'text' => str_random(),
        ];

        $question->choices()->create($option);
        $this->assertDatabaseHas('question_options', $option);
    }
}
