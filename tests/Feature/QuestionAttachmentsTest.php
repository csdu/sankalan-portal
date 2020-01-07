<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\QuestionAttachment;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionAttachmentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_not_see_question_attachment_if_quiz_has_not_started()
    {
        $questionAttachment = create(QuestionAttachment::class);

        $this->signIn();

        $res = $this->get("question_attachments/{$questionAttachment->id}");
        $res->assertForbidden();
    }

    /** @test */
    public function user_can_see_question_attachment_if_quiz_has_started()
    {
        $quiz = create(Quiz::class);
        $this->signInAdmin();

        $question = create(Question::class, 1, [
            'quiz_id' => $quiz->id,
        ]);

        $questionAttachment = create(QuestionAttachment::class, 1, [
            'question_id' => $question->id,
        ]);

        $quiz->setActive();

        $this->signIn();

        $res = $this->get("question_attachments/{$questionAttachment->id}");
        $res->assertStatus(500);
    }
}
