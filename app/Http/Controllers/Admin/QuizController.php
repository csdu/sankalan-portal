<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Quiz;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withCount(['participations', 'questions'])
            ->with(['event'])
            ->get();

        return view('admin.quizzes.index', compact('quizzes'));
    }
    
    public function goLive(Quiz $quiz)
    {
        if(!$quiz->setActive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'quiz' => $quiz
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Live!',
            'quiz' => $quiz
        ];
    }

    public function close(Quiz $quiz)
    {
        if(!$quiz->setOffline()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'quiz' => $quiz
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Closed!',
            'quiz' => $quiz
        ];
    }
}
