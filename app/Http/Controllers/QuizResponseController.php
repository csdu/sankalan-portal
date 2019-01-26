<?php

namespace App\Http\Controllers;

use App\Checks\TeamCanSubmitQuizResponse;
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

        $team = $quiz->event->participatingTeamByUser(Auth::user());
        
        if(!TeamCanSubmitQuizResponse::check($team, $quiz)) {
            return redirect()->back();
        }
        
        $team->endQuiz($quiz, request('responses') ?? []);

        if ($quiz->isTimeLimitExceeded($team)) {
            flash('You tried messing with timer, you are disqualified!')->error();
        } else {
            flash('Your response has been recorded! All The Best!')->success();
        }

        return redirect()->back();
    }
}
