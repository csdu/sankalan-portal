<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());
        if(!$team) {
            flash('You are not participating in this event!')->error();
            return redirect()->route('dashboard');
        }
        if(!$quiz->isTeamAllowed($team)) {
            flash('You are not allowed for this Quiz! Please contact hekp desk.')->error();
            return redirect()->route('dashboard');
        }
        flash('Quiz is not yet active')->error();
        return redirect()->route('dashboard');
    }
}
