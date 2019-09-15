<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class QuizResponseController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $this->authorize('create_response', $quiz);

        $data = $request->validate([
            'responses' => ['sometimes', 'array', "max:{$quiz->questions()->count()}"],
            'responses.*.response_keys' => ['required', 'string'],
            'responses.*.question_id' => ['required', 'integer', 'exists:questions,id'],
        ]);

        $team = $quiz->event->participatingTeamByUser(Auth::user());

        $team->endQuiz($quiz, $data['responses'] ?? []);

        if ($quiz->isTimeLimitExceeded($team)) {
            flash('Your time limit exceeded!')->error();
            $status = Response::HTTP_REQUEST_TIMEOUT;
        } else {
            flash('Your response has been recorded! All The Best!')->success();
            $status = Response::HTTP_ACCEPTED;
        }

        return $this->getJsonOrRedirect($status);
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
