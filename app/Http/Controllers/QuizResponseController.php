<?php

namespace App\Http\Controllers;

use App\Checks\TeamCanSubmitQuizResponse;
use Illuminate\Http\Request;
use App\Quiz;
use Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class QuizResponseController extends Controller
{
    public function store(Quiz $quiz)
    {
        $data = request()->validate([
            'responses' => ['sometimes', 'array', "max:{$quiz->questions()->count()}"],
            'responses.*.response_keys' => ['required', 'string'],
            'responses.*.question_id' => ['required', 'integer', 'exists:questions,id'],
        ]);

        $team = $quiz->event->participatingTeamByUser(Auth::user());

        if (!TeamCanSubmitQuizResponse::check($team, $quiz)) {
            return $this->getJsonOrRedirect(Response::HTTP_FORBIDDEN);
        }

        $team->endQuiz($quiz, $data['responses'] ?? []);

        if ($quiz->isTimeLimitExceeded($team)) {
            flash('Your time limit exceeded, you are disqualified!')->error();
            $status = Response::HTTP_REQUEST_TIMEOUT;
        } else {
            flash('Your response has been recorded! All The Best!')->success();
            $status = Response::HTTP_ACCEPTED;
        }

        return $this->getJsonOrRedirect($status);
    }

    private function getJsonOrRedirect($status = 202)
    {
        if (!request()->expectsJson()) {
            return redirect()->back();
        }

        return response()->json([
            'message' => Session::pull('flash_notification')->toArray()[0],
        ], $status);
    }
}
