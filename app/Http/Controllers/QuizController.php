<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        if(!$quiz->isActive()) {
            flash('Quiz is not yet active')->error();
            return redirect()->route('dashboard');
        }
        $team = $quiz->event->participatingTeamByUser(Auth::user());
        if(!$team) {
            flash('You are not participating in this event!')->error();
            return redirect()->route('dashboard');
        }
        if(!$quiz->isTeamAllowed($team)) {
            flash('You are not allowed for this Quiz! Please contact help desk.')->error();
            return redirect()->route('dashboard');
        }
        $quiz->participationByTeam($team)->update(['started_at' => now()]);
        return view('quiz.index')->withQuiz($quiz->load('questions'));
    }
}
