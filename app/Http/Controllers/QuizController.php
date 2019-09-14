<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Team;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Request $request, Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        
        $teamId = auth()->user()->teams->pluck('id')->intersect($quiz->event->teams->pluck('id'))->first();

        $team = Team::find($teamId);
        
        $quiz->loadMissing([
            'questions.choices',
            'participations' => function ($query) use ($teamId) {
                $query->where('quiz_participations.team_id', $teamId);
            },
        ]);

        $team->beginQuiz($quiz);

        return view('quizzes.show', compact('quiz'));
    }

    public function instructions(Quiz $quiz)
    {
        $quiz->load(['event']);

        return view('quizzes.instructions', compact('quiz'));
    }
}
