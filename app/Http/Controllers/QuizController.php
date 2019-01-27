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

        $quiz->participationByTeam($team)->update(['started_at' => now()]);

        return view('quiz.index')->withQuiz($quiz->load('questions.choices'));
    }
}
