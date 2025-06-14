<?php

namespace Tests\Unit;

use App\Models\QuestionOption;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function question_has_many_options()
    {
        $question = create(Question::class);
        $options = create(QuestionOption::class, 4, ['question_id' => $question->id]);

        tap($question->choices, function ($relatedChoices) use ($options) {
            $this->assertInstanceOf(Collection::class, $relatedChoices);
            $this->assertCount(4, $relatedChoices);
            $this->assertSame($options->pluck('id')->toArray(), $relatedChoices->pluck('id')->toArray());
        });
    }
}
