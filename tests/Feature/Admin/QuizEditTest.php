<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_sees_edit_form()
    {
        $quiz = create(Quiz::class, 1);

        $this->withoutExceptionHandling()->signInAdmin();
        $res = $this->get(route('admin.quizzes.edit', $quiz));

        $res->assertOk();
    }

    /** @test */
    public function admin_can_edit_an_event()
    {
        $quiz = create(Quiz::class, 1);

        $this->signInAdmin();

        $data = [
            'title' => 'quiz title',
            'slug' => 'quiz-title',
            'instructions' => 'this is instructions',
            'time_limit' => 1800,
            'questions_limit' => 50,
            'event_id' => create(Event::class, 1)->id,
        ];

        $res = $this->patch(route('admin.quizzes.update', $quiz), $data);
        $res->assertRedirect(route('admin.quizzes.index'));
    }
}
