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
        $questionAttachments = $question->attachments;

        return view('admin.questions.show', compact('question', 'quizTitle', 'questionAttachments'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        abort_if($quiz->isactive, 403);

        $request->validate([
            'qno' => 'required|numeric|gt:0',
            'positive_score' => 'required|numeric|gte:1',
            'negative_score' => 'required|numeric|gte:0',
            'text' => 'required',
            'compiledHTML' => 'required',
            'type' => 'required|in:mcq,input',
            'illustrations' => 'nullable|array|max:4',
            'illustrations.*' => 'required|file|image',
            'options' => 'required_if:type,mcq|array',
            'options.*' => 'required',
            'correct_answer_keys' => 'required_if:type,input|string',
            'correct_answer_index' => 'required_if:type,mcq|numeric|gte:0|lt:'.count($request->options ?? []),
        ]);

        $question = $quiz->questions()->create([
            'qno' => $request->qno,
            'positive_score' => $request->positive_score,
            'negative_score' => $request->negative_score,
            'text' => $request->compiledHTML,
            'correct_answer_keys' => $request->correct_answer_keys,
        ]);

        foreach ($request->illustrations ?? [] as $illustration) {
            $question->attachments()->create([
                'path' => $illustration->store('illustrations'),
            ]);
        }

        $options = $question->choices()->createMany(
            array_map(function ($option) use ($question) {
                return [
                    'key' => strval($question->id).str_random(3),
                    'text' => $option,
                ];
            }, $request->options ?? [])
        );

        if ($request->type == 'mcq') {
            $question->update([
                'correct_answer_keys' => $options[$request->correct_answer_index]->key,
            ]);
        }

        flash("Question created for {$quiz->title}!")->success();

        return redirect()->route('admin.quizzes.questions.create', $quiz, '201');
    }

    public function delete(Quiz $quiz, Question $question)
    {
        // can only delete question if quiz is not active
        abort_if($quiz->isActive, 403, "You can only delete question if quiz isn't active");

        $question->delete();

        flash('Question deleted', 'success');

        return redirect()->route('admin.quizzes.show', $quiz);
    }

    public function edit(Quiz $quiz, Question $question)
    {
        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        abort_if($quiz->isactive, 403);

        $request->validate([
            'qno' => 'required|numeric|gt:0',
            'positive_score' => 'required|numeric|gte:1',
            'negative_score' => 'required|numeric|gte:0',
            'text' => 'required',
            'compiledHTML' => 'required',
        ]);

        $question->update([
            'qno' => $request->qno,
            'positive_score' => $request->positive_score,
            'negative_score' => $request->negative_score,
            'text' => $request->compiledHTML,
        ]);

        flash('Question updated!')->success();

        return redirect()->route('admin.quizzes.show', $quiz);
    }
}
