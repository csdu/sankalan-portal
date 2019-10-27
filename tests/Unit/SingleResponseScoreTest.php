<?php

namespace Tests\Unit;

use App\Models\QuestionOption;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SingleResponseScoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function multi_choice_questions_are_marked_according_to_questions_scheme()
    {
        $quiz = create(Quiz::class);
        $quizResponses = create(QuizResponse::class, 2, ['quiz_id' => $quiz->id]);
        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
            'positive_score' => 4,
            'negative_score' => 1,
        ]);
        $choices = create(QuestionOption::class, 4, ['question_id' => $question->id]);
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
    }

    /** @test */
    public function word_phrase_questions_are_marked_correctly_according_to_questions_scheme()
    {
        $quiz = create(Quiz::class);
        $quizResponses = create(QuizResponse::class, 2, ['quiz_id' => $quiz->id]);
        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
            'positive_score' => 4,
            'negative_score' => 1,
            'correct_answer_keys' => [
                'correct answer',
                'answer correct',
                '   is correct',
                'correct   ',
            ],
        ]);

        $correctResponse = $quiz->participations[0]->responses()->create([
            'question_id' => $question->id,
            'response_keys' => 'correct answer',
        ]);
        $this->assertEquals($question->positive_score, $correctResponse->score);

        $correctResponse = $quiz->participations[0]->responses()->create([
            'question_id' => $question->id,
            'response_keys' => '    correct     answer     ',
        ]);
        $this->assertEquals($question->positive_score, $correctResponse->score);

        $correctResponse = $quiz->participations[0]->responses()->create([
            'question_id' => $question->id,
            'response_keys' => 'coRrEcT   ',
        ]);
        $this->assertEquals($question->positive_score, $correctResponse->score);

        $inCorrectResponse = $quiz->participations[0]->responses()->create([
            'question_id' => $question->id,
            'response_keys' => 'in coRrEcT   ',
        ]);
        $this->assertEquals(-$question->negative_score, $inCorrectResponse->score);
    }
}
