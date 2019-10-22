<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Session;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function quiz_belongs_to_an_event()
    {
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);

        tap($quiz->event, function ($relatedEvent) use ($event) {
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
    public function quiz_has_a_time_limit_with_1800_as_default()
    {
        $quiz = make(Quiz::class);
        unset($quiz->time_limit);
        $quiz->save();

        $this->assertEquals(1800, $quiz->fresh()->time_limit);

        $quiz->update(['time_limit' => 2700]);
        $this->assertEquals(2700, $quiz->fresh()->time_limit);
    }

    /** @test */
    public function quiz_verifies_token()
    {
        $quiz = create(Quiz::class, 1, ['token' => $token = 'valid_token']);

        $this->assertFalse($quiz->verify('invalid_token'));
        $this->assertFalse(Session::has('quiz_token'));

        $this->assertTrue($quiz->verify($token));
        $this->assertTrue(Session::has('quiz_token'));
        $this->assertEquals($token, Session::get('quiz_token'));
    }
}
