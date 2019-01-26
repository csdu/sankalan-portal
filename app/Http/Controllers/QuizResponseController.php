<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quiz;
use Auth;

class QuizResponseController extends Controller
{
    public function store(Quiz $quiz)
    {
        $data = request()->validate([
            'responses' => ['sometimes','array', 'min:1', "max:{$quiz->questions()->count()}"],
            'responses.*.response_key' => ['required', 'string'],
            'responses.*.question_id' => ['required', 'integer', 'exists:questions,id'],
        ]);

        if (!$quiz->isActive()) {
            flash('Quiz is not yet active')->error();
        } else if (!$team = $quiz->event->participatingTeamByUser(Auth::user())) {
            flash('You are not participating in this event!')->error();
        } else if (!$quiz->isTeamAllowed($team)) {
            flash('You are not allowed for this Quiz! Please contact help desk.')->error();
        } else if (!$quiz->participationByTeam($team)->started_at) {
            flash('You tried something fishy, you are disqualified!')->error();
        } else if ($quiz->participationByTeam($team)->finished_at != null) {
            flash('You tried something fishy, you are disqualified!')->error();
        } else if (
            $quiz->participationByTeam($team)->started_at->diffInMinutes(now()) > ($quiz->timeLimit + 2)
        ) {
            flash('You tried something fishy, you are disqualified!')->error();
            $team->endQuiz($quiz, request('responses') ?? []);
        } else {
            flash('Your response has been recorded! All The Best!')->success();
            $team->endQuiz($quiz, request('responses') ?? []);
        }

        return redirect()->back();
    }
}
