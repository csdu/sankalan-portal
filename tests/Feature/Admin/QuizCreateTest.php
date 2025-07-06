<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuizCreateTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_sees_create_form()
    {
        $this->withoutExceptionHandling()->signInAdmin();
        $res = $this->get(route('admin.quizzes.create'));
        $res->assertOk();
    }

    #[Test]
    public function admin_can_create_a_quiz()
    {
        $event = create(Event::class, 1);
        $this->withoutExceptionHandling()->signInAdmin();

        $data = [
            'title' => 'quiz title',
            'slug' => 'quiz-title',
            'instructions' => 'quiz instruction here',
            'time_limit' => 1800,
            'questions_limit' => 50,
            'event_id' => $event->id,
        ];

        $this->post(route('admin.quizzes.store'), $data);
        $this->assertDatabaseHas('quizzes', $data);
    }
}
