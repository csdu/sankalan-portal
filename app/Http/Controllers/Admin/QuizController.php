<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Quiz;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    public function goLive(Quiz $quiz)
    {
        if(!$quiz->setActive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Live!'
        ];
    }

    public function close(Quiz $quiz)
    {
        if(!$quiz->setOffline()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Closed!'
        ];
    }
}
