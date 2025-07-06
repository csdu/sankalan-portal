<?php

namespace App\Http\Controllers;

use App\Models\QuestionResponse;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionResponseController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());

        $team->endQuiz($quiz);

        if ($quiz->isTimeLimitExceeded($team)) {
            flash('Your time limit exceeded!')->error();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your time limit exceeded!'
                ], Response::HTTP_FORBIDDEN);
            }

            return redirect()->back();
        }

        flash('Your response has been recorded! All The Best!')->success();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Your response has been recorded! All The Best!'
            ], Response::HTTP_OK);
        }

        return redirect()->back();
    }

    public function saveQuestionResponse(Request $request, Quiz $quiz)
    {
        $team = $quiz->event->participatingTeamByUser(Auth::user());

        if (!$quiz->isVerified()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Quiz must be verified before submitting responses.'
                ], Response::HTTP_FORBIDDEN);
            }

            flash('Quiz must be verified before submitting responses.')->error();
            return redirect()->back();
        }

        $quizResponseId = $quiz->participationByTeam($team)->id;
        $questionId = $request->question_id;
        $responseKey = $request->response_key;

        $questionResponse = QuestionResponse::updateOrCreate(
            ['question_id' => $questionId, 'quiz_response_id' => $quizResponseId],
            ['response_keys' => $responseKey]
        );


        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your response has been saved!',
                'question_response' => [
                    'question_id' => $questionId,
                    'question_response_id' => $questionResponse->id,
                    'response_key' => $responseKey,
                ]
            ]);
        }

        flash('Your response has been saved!')->success();
        return redirect()->back();
    }
}
