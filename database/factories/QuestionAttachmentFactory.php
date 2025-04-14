<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuestionAttachmentFactory extends Factory
{
    protected $model = QuestionAttachment::class;

    public function definition()
    {
        return [
            'question_id' => Question::factory()->create()->id,
            'path' => 'illustrations/'.Str::random(),
        ];
    }
}
