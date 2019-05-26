<?php

namespace App\Http\Controllers;

use App\Checks\TeamCanTakeQuiz;
use App\Quiz;
use Auth;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());

        if ( ! TeamCanTakeQuiz::check($team, $quiz)) {
            return redirect()->route('dashboard');
        }

        $quiz->load([
            'questions.choices',
            'participations' => function ($query) use ($team) {
                $query->where('quiz_participations.team_id', $team->id);
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
