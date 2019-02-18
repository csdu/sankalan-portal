<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\QuizParticipation;
use App\QuizResponse;

class ViewQuizParticipationDetailsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function view_quiz_participation_details_with_all_responses_and_individual_scores() {
        $this->signInAdmin();
        
        $users = create('App\User', 2);
        $team = $users[0]->createTeam('Team', $users[1]);
        $quizParticipation = create('App\QuizParticipation');
        $team->participate($quizParticipation->quiz->event);
        $questions = create('App\Question', 10, ['quiz_id' => $quizParticipation->quiz->id]);
        $questions->each(function($question) use ($quizParticipation) {
            create('App\AnswerChoice', 4, ['question_id' => $question->id]);
            QuizResponse::create([
                'quiz_participation_id' => $quizParticipation->id,
                'response_keys' => $question->choices->random()->key,
                'question_id' => $question->id
            ]);
        });

        $participation = $this->withoutExceptionHandling()
            ->get(route('admin.quiz-participations.show', $quizParticipation))
            ->assertSuccessful()
            ->viewData('quizParticipation');

        $this->assertInstanceOf(QuizParticipation::class, $participation);

        tap($participation->toArray(), function($participation) {
            $this->assertArrayHasKey('team', $participation);
            $this->assertArrayHasKey('responses', $participation);
            $this->assertArrayHasKey('members', $participation['team']);
            $this->assertCount(10, $participation['responses']);
            $this->assertArrayHasKey('question', $participation['responses'][0]);
            $this->assertArrayHasKey('choices', $participation['responses'][0]['question']);
        });
    }
}
