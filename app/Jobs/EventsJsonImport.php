<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Event;
use App\Quiz;
use App\Question;
use App\AnswerChoice;

class EventsJsonImport
{
    use Dispatchable, Queueable;

    protected $file;
    protected $events;
    protected $quizzes;
    protected $questions;
    protected $choices;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->events = collect([]);
        $this->quizzes = collect([]);
        $this->questions = collect([]);
        $this->choices = collect([]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->readFromFile()
            ->createEvents()
            ->createQuizzes()
            ->createQuestions()
            ->createChoices();
    }

    public function readFromFile()
    {
        $contents = file_get_contents($this->file);
        $this->events = collect(json_decode($contents, true))->recursive();
        return $this;
    }
    
    public function createEvents() {
        $this->events->transform(function($event) {
            $quizzes = $event->pull('quizzes') ?? collect([]);

            if(!is_string($event['description'])) {
                $event['description'] = $event['description']->implode('\n');
            }
            
            $event = Event::create($event->toArray());

            $this->quizzes = $this->quizzes->concat(
                $quizzes->map->prepend($event->id, 'event_id')
            );
            
            return $event;
        });
        return $this;
    }

    public function createQuizzes() {
        $this->quizzes->transform(function ($quiz) {
            $questions = $quiz->pull('questions') ?? collect([]);

            $quiz['time_limit'] = $quiz['time_limit'] * 60;

            $quiz = Quiz::create($quiz->toArray());

            $this->questions = $this->questions->concat(
                $questions->map->prepend($quiz->id, 'quiz_id')
            );
            return $quiz;
        });
        return $this;
    }

    public function createQuestions()
    {
        $this->questions->transform(function ($question) {
            $choices = $question->pull('answer_choices') ?? collect([]);
            if(!is_string($question['text'])) {
                $question['text'] = $question['text']->implode(' ');
            }

            if ($question->has('code') && !is_string($question['code'])) {
                $question['code'] = $question['code']->implode('<br>');
            }

            if ($question->has('correct_answer_keys') && !is_string($question['correct_answer_keys'])) {
                $question['correct_answer_keys'] = $question['correct_answer_keys']->implode(':');
            }

            $question = Question::create($question->toArray());
            $this->choices = $this->choices->concat(
                $choices->map->prepend($question->id, 'question_id')
            );
            return $question;
        });
        return $this;
    }
 #endregion
    public function createChoices()
    {
        $this->choices->map(function ($choice) {
            if ($choice->has('code') && !is_string($choice['code'])) {
                $choice['code'] = $choice['code']->implode('<br>');
            }

            return AnswerChoice::create($choice->toArray());
        });
        return $this;
    }
}
