<?php

namespace App\Http\Controllers;

use App\Checks\TeamCanTakeQuiz;
use Illuminate\Http\Request;
use App\Quiz;
use App\User;
use App\Team;
use Auth;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());

        if(!TeamCanTakeQuiz::check($team, $quiz)) {
            return redirect()->route('dashboard');
        }

        $quiz->load([
            'questions.choices', 
            'participations' => function($query) use ($team) {
                $query->where('quiz_participations.team_id', $team->id);
            },
        ]);

        $team->beginQuiz($quiz);

        return view('quiz.index', compact('quiz'));
    }
}
