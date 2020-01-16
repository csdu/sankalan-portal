<?php

namespace Tests\Feature\Admin;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class QuestionCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_sees_create_form()
    {
        $quiz = create(Quiz::class);

        $this->signInAdmin();
        $res = $this->get(route('admin.quizzes.questions.create', $quiz));
        $res->assertOk();
    }

    /** @test */
    public function admin_can_create_a_question()
    {
        Storage::fake();

        $this->signInAdmin();
        $quiz = create(Quiz::class);

        $this->withoutExceptionHandling()
            ->post('/manage/quizzes/'.$quiz->slug.'/questions', $params = [
                'qno' => 1,
                'positive_score' => 4,
                'negative_score' => 1,
                'text' => 'question is written as **markdown**',
                'compiledHTML' => '<p>question is written as <b>markdown</b></p>',
                'type' => 'mcq',
                'illustrations' => [UploadedFile::fake()->image('illustration.jpg')],
                'options' => $options = [
                    'options 1',
                    'options 2',
                    'options 3',
                    'options 4',
                ],
                'correct_answer_index' => $correct_answer_index = 2, // 'option 3' index
            ])->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertCount(1, $questions = Question::all());
        $this->assertEquals($params['qno'], $questions->first()->qno);
        $this->assertEquals($params['positive_score'], $questions->first()->positive_score);
        $this->assertEquals($params['negative_score'], $questions->first()->negative_score);
        $this->assertEquals($params['compiledHTML'], $questions->first()->text);
        $this->assertCount(count($options), $choices = $questions->first()->choices);

        $expectedCorrectKey = $questions->first()->choices()
            ->whereText($options[$correct_answer_index])
            ->first()->key;

        $this->assertEquals($expectedCorrectKey, $questions->first()->correct_answer_keys->first());
    }
}
