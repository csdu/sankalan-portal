<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Event;
use App\Quiz;

class QuizGoesLiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function quiz_is_set_active_in_the_event()
    {
        $admin = create(User::class, 1, ['is_admin' => true]);
        $events = create(Event::class, 2, ['is_live' => true]);
        $quizzes = create(Quiz::class, 2, ['event_id' => $events[0]->id]);

        $this->withoutExceptionHandling()->be($admin);

        $response = $this->postJson(route('quizzes.go-live', $quizzes[0]))
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals($quizzes[0]->id, $events[0]->fresh()->active_quiz_id);
    }
}
