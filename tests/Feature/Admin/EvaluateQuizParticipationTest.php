<?php

namespace Tests\Feature\Admin;

use App\Models\AnswerChoice;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizParticipation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluateQuizParticipationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function evaluator_sums_up_each_questions_response_in_the_quiz_team_participation()
    {
        $quiz = create(Quiz::class);

        $questions = create(Question::class, 5, [
            'quiz_id' => $quiz->id,
            'positive_score' => 4,
            'negative_score' => 1,
        ]);

        $questions->each(function ($question) {
            $choices = create(AnswerChoice::class, 4, ['question_id' => $question->id]);
            $question->update(['correct_answer_keys' => $choices->random()->key]);
        });

        $twoCorrectResponses = $quiz->questions->take(2)->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->correct_answer_keys->implode(':'),
            ];
        });
        $threeInCorrectResponses = $quiz->questions->take(-3)->map(function ($question) {
            return [
                'question_id' => $question->id,
                'response_keys' => $question->choices->map->key->diff($question->correct_answer_keys)->first(),
            ];
        });

        $participation = create(QuizParticipation::class, 1, ['quiz_id' => $quiz->id]);
        $participation->recordResponses($twoCorrectResponses->union($threeInCorrectResponses)->toArray());

        $json = $this->withoutExceptionHandling()
            ->signInAdmin()
            ->postJson(
                route('admin.quizzes.teams.evaluate', $participation)
            )->assertSuccessful()->json();

        $this->assertArrayHasKey('status', $json);
        $this->assertArrayHasKey('message', $json);
        $this->assertArrayHasKey('score', $json);

        $this->assertEquals((3 * (-1)) + (2 * 4), $json['score']);
        $this->assertEquals((3 * (-1)) + (2 * 4), $participation->fresh()->score);
    }
}
