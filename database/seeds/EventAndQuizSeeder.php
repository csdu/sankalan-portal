<?php

use Illuminate\Database\Seeder;

class EventAndQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Event', 4)->states('withoutQuiz')->create();
        factory('App\Event', 8)->create()
        ->each(function($event) {
            factory('App\Quiz', $event->rounds-1)
            ->create(['event_id' => $event->id])
            ->each(function($quiz) {
                factory('App\Question', $quiz->questionsLimit)->create(['quiz_id' => $quiz->id])
                ->each(function($question) {
                    $options = factory('App\AnswerChoice', 4)->create(['question_id' => $question->id]);
                    $answer_keys = $options->random(rand(1, 4))->pluck('id')->implode(':');
                    $question->update(compact('answer_keys'));
                });
            });
        });
    }
}
