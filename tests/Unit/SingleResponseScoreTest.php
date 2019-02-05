<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Question;
use App\AnswerChoice;
use App\Team;
use App\Quiz;
use App\QuizResponse;
use App\QuizParticipation;

class SingleResponseScoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function evaluator_evaluates_positive_score_for_correct_answer_acc_to_question_marking_scheme()
    {
        $quiz = create(Quiz::class);
        $quizParticipations = create(QuizParticipation::class, 2, ['quiz_id' => $quiz->id]);
        $question = create(Question::class, 1, ['quiz_id' => $quiz->id, 'positive_score' => 4, 'negative_score' => 1]);
        $choices = create(AnswerChoice::class, 4, ['question_id' => $question->id]);
        $question->update(['correct_answer_keys' => $choices->random()->key]);
        $question = $question->fresh();
        
        $correctResponse = $quiz->participations[0]->responses()->create([
            'question_id' => $question->id,
            'response_keys' => $question->correct_answer_keys->implode(':'),
        ]);

        $inCorrectResponse = $quiz->participations[1]->responses()->create([
            'question_id' => $question->id,
            'response_keys' => $question->choices->map->key->diff($question->correct_answer_keys)->random(),
        ]);

        $this->assertEquals($question->positive_score, $correctResponse->score);
        $this->assertEquals(-$question->negative_score, $inCorrectResponse->score);

    }
}
