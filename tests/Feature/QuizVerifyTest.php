<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizVerifyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     *
     */
    public function test_user_should_redirect_to_quiz_verify_page_if_quiz_is_not_verified()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id]);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user);

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $response = $this->get(route('quizzes.show', $quiz));
        $response->assertRedirect(route('quizzes.verify', $quiz));
    }

    public function test_user_should_redirect_to_show_quiz_if_he_enters_correct_code()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => '1234567']);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user)->withoutExceptionHandling();

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $response = $this->post(route('quizzes.verify', $quiz), [
            'verification_token' => '1234567',
        ]);

        $response->assertRedirect(route('quizzes.show', $quiz));
    }

    public function test_user_should_redirect_to_verify_quiz_if_he_enters_wrong_code()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => '1234567']);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user)->withoutExceptionHandling();

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $response = $this->post(route('quizzes.verify', $quiz), [
            'verification_token' => 'wrongcode',
        ]);

        $response->assertRedirect(route('quizzes.verify', $quiz));
    }

    public function test_user_can_take_quiz_if_quiz_is_verified()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => '1234567']);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user)->withoutExceptionHandling();

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $response = $this->withSession(['quiz_token' => '1234567'])->get(route('quizzes.show', $quiz));
        $response->assertStatus(200);
    }

    public function test_user_when_goes_to_verify_quiz_is_redirected_to_quiz_if_verified_the_token()
    {
        $user = create(User::class);
        $event = create(Event::class);
        $quiz = create(Quiz::class, 1, ['event_id' => $event->id, 'token' => '1234567']);
        $questions = create(Question::class, 10, ['quiz_id' => $quiz->id]);
        $questions->each(function ($question) {
            create(QuestionOption::class, 4, ['question_id' => $question->id]);
        });

        $this->be($user)->withoutExceptionHandling();

        $team = $user->createTeam($user->name);
        $team->participate($event);
        $quiz->setActive();

        $response = $this->withSession(['quiz_token' => '1234567'])->get(route('quizzes.verify', $quiz));
        $response->assertRedirect(route('quizzes.take', $quiz));
    }
}
