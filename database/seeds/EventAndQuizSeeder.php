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
        factory('App\Models\Event', 8)->create()
        ->each(function ($event) {
            factory('App\Models\Quiz', $event->rounds - 1)
            ->create(['event_id' => $event->id])
            ->each(function ($quiz) {
                factory('App\Models\Question', $quiz->questionsLimit)->create(['quiz_id' => $quiz->id])
                ->each(function ($question) {
                    $options = factory('App\Models\QuestionOption', 4)->create(['question_id' => $question->id]);
                    $correct_answer_keys = $options->random(rand(1, 4))->pluck('key')->implode(':');
                    $question->update(compact('correct_answer_keys'));
                });
            });
        });
    }
}
