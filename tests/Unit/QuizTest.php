<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Event;
use App\Quiz;
use App\Question;
use Illuminate\Support\Collection;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function quiz_belongs_to_an_event()
    {
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        
        tap($quiz->event, function($relatedEvent) use ($event) {
            $this->assertInstanceOf(Event::class, $relatedEvent);
            $this->assertEquals($event->id, $relatedEvent->id);
        });
    }

    /** @test */
    public function quiz_has_many_questions()
    {
        $quiz = create(Quiz::class);
        $questions = create(Question::class, 3, ['quiz_id' => $quiz->id]);

        tap($quiz->questions, function ($relatedQuestions) use ($quiz, $questions) {
            $this->assertInstanceOf(Collection::class, $relatedQuestions);
            $this->assertCount(3, $relatedQuestions);
            $this->assertSame($relatedQuestions->pluck('id')->toArray(), $questions->pluck('id')->toArray());
        });
    }

    /** @test */
    public function quiz_has_a_time_limit_with_30_as_default()
    {
        $quiz = create(Quiz::class)->fresh();
        $this->assertEquals(30, $quiz->timeLimit);

        $quiz->update(['timeLimit' => 45]);
        $this->assertEquals(45, $quiz->fresh()->timeLimit);
    }
}
