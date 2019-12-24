<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Quiz $quiz)
    {
        return view('admin.questions.create', compact('quiz'));
    }

    public function show(Quiz $quiz, Question $question)
    {
        $quizTitle = $quiz->title;
        return view('admin.questions.show', compact('question', 'quizTitle'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        abort_if($quiz->isactive, 403);

        request()->validate([
            'qno' => 'required|numeric|gt:0',
            'positive_score' => 'required|numeric|gte:1',
            'negative_score' => 'required|numeric|gte:0',
            'correct_answer_keys' => 'sometimes|string',
            'options' => 'sometimes|array',
        ]);

        $question = $quiz->questions()->create([
            'qno' => $request->qno,
            'positive_score' => $request->positive_score,
            'negative_score' => $request->negative_score,
            'text' => $request->text,
            'correct_answer_keys' => $request->correct_answer_keys ?? null,
        ]);

        if ($request->options) {
            foreach ($request->options as $option) {
                $question->choices->create([
                    'key' => strval($question->id).str_random(3),
                    'text' => $option,
                ]);
            }
        }

        flash("Question created for {$quiz->title}!")->success();

        return redirect()->route('admin.quizzes.questions.create', $quiz);
    }

    public function delete(Quiz $quiz, Question $question)
    {
        // can only delete question if quiz is not active
        abort_if($quiz->isActive, 403, "You can only delete question if quiz isn't active");

        $question->delete();

        flash('Question deleted', 'success');

        return redirect()->route('admin.quizzes.show', $quiz);
    }
}
