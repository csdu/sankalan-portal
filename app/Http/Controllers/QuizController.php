<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Team;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Request $request, Quiz $quiz)
    {
        $teamId = auth()->user()->teams->pluck('id')->intersect($quiz->event->teams->pluck('id'))->first();
        
        $team = Team::find($teamId);

        $quiz->loadMissing('questions.choices');
        
        $participation = $team->beginQuiz($quiz);

        return view('quizzes.show', compact('quiz', 'participation'));
    }

    public function instructions(Quiz $quiz)
    {
        $quiz->load(['event']);

        return view('quizzes.instructions', compact('quiz'));
    }
}
