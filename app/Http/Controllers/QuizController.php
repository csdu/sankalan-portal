<?php

namespace App\Http\Controllers;

use App\Models\QuestionAttachment;
use App\Models\Quiz;
use App\Models\Team;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Request $request, Quiz $quiz)
    {
        $teamId = auth()->user()->teams->pluck('id')->intersect($quiz->event->teams->pluck('id'))->first();

        $team = Team::find($teamId);

        $quiz->loadMissing(['questions.choices']);

        $participation = $team->beginQuiz($quiz);

        $quiz_response = $quiz->participations()->with(['responses'])->where('team_id', $teamId)->first();

        $questionIds = collect($quiz->questions)->map(function ($question) {
            return $question->id;
        });

        $questionsAttachments = QuestionAttachment::whereIn('question_id', $questionIds)->get();

        return view('quizzes.show', compact('quiz', 'participation', 'questionsAttachments', 'quiz_response'));
    }

    public function instructions(Quiz $quiz)
    {
        $quiz->load(['event']);

        return view('quizzes.instructions', compact('quiz'));
    }
}
