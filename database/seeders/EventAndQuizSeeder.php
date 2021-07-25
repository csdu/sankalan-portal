<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
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
        Event::factory()->count(5)->create()
            ->each(function ($event) {
                Quiz::factory()->count($event->rounds)
                    ->create(['event_id' => $event->id])
                    ->each(function ($quiz) {
                        Question::factory()->count($quiz->questions_limit)
                            ->create(['quiz_id' => $quiz->id])
                            ->each(function ($question) {
                                $options = QuestionOption::factory()->count(4)->create(['question_id' => $question->id]);
                                $correct_answer_keys = $options->random(rand(1, 4))->pluck('key')->implode(':');
                                $question->update(compact('correct_answer_keys'));
                            });
                    });
            });
    }
}
