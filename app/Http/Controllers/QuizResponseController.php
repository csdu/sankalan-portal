<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class QuizResponseController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'responses' => ['sometimes', 'array', "max:{$quiz->questions()->count()}"],
            'responses.*.response_keys' => ['required', 'string'],
            'responses.*.question_id' => ['required', 'integer', 'exists:questions,id'],
        ]);

        $team = $quiz->event->participatingTeamByUser(Auth::user());

        $team->endQuiz($quiz, $data['responses'] ?? []);

        if ($quiz->isTimeLimitExceeded($team)) {
            flash('Your time limit exceeded!')->error();
            return $this->getJsonOrRedirect(Response::HTTP_UNAUTHORIZED);
        }
        
        flash('Your response has been recorded! All The Best!')->success();
        return $this->getJsonOrRedirect(Response::HTTP_ACCEPTED);
    }

    private function getJsonOrRedirect($status = 202)
    {
        if (! request()->expectsJson()) {
            return redirect()->back();
        }

        return response()->json([
            'message' => Session::pull('flash_notification')->toArray()[0],
        ], $status);
    }
}
