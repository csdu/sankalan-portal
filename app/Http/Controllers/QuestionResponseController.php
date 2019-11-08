<?php

namespace App\Http\Controllers;

use App\Models\QuestionResponse;
use App\Models\Quiz;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class QuestionResponseController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());

        $team->endQuiz($quiz);

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

    public function saveQuestionResponse(Request $request, Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());
        $quizResponseId = $quiz->participationByTeam($team)->id;

        $questionId = $request->question_id;
        $responseKey = $request->response_key;

        $questionResponse = QuestionResponse::updateOrCreate(
            ['question_id' => $questionId, 'quiz_response_id' => $quizResponseId],
            ['response_keys' => $responseKey]
        );

        return response()->json([
            'message' => 'Your response has been saved!',
            'question_response' => [
                'question_id' => $questionId,
                'question_response_id' => $questionResponse->id,
                'response_key' => $responseKey,
            ],
        ]);
    }
}
