<?php

namespace App\Jobs;

use App\Models\AnswerChoice;
use App\Models\Event;
use App\Models\Question;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportQuizToEvent
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
    public function __construct($file, Event $event)
    {
        $this->file = $file;
        $this->event = $event;
        $this->quiz = collect([]);
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
            ->createQuiz()
            ->createQuestions()
            ->createChoices();
    }

    public function readFromFile()
    {
        $contents = file_get_contents($this->file);
        $this->quiz = collect(json_decode($contents, true))->recursive();

        return $this;
    }

    public function createQuiz()
    {
        $questions = $this->quiz->pull('questions') ?? collect([]);

        $this->quiz['time_limit'] *= 60;

        $this->quiz = $this->event->quizzes()->create($this->quiz->toArray());

        $this->questions = $this->questions->concat(
            $questions->map->prepend($this->quiz->id, 'quiz_id')
        );

        return $this;
    }

    public function createQuestions()
    {
        $this->questions->transform(function ($question) {
            $choices = $question->pull('answer_choices') ?? collect([]);
            if (! is_string($question['text'])) {
                $question['text'] = $question['text']->implode(' ');
            }

            if ($question->has('code') && ! is_string($question['code'])) {
                $question['code'] = $question['code']->implode('<br>');
            }

            if ($question->has('correct_answer_keys') && ! is_string($question['correct_answer_keys'])) {
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

    public function createChoices()
    {
        $this->choices->map(function ($choice) {
            if ($choice->has('code') && ! is_string($choice['code'])) {
                $choice['code'] = $choice['code']->implode('<br>');
            }

            if ($choice->has('text') && ! is_string($choice['text'])) {
                $choice['text'] = $choice['text']->implode(' ');
            }

            return AnswerChoice::create($choice->toArray());
        });

        return $this;
    }
}
