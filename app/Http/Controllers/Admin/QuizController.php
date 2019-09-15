<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
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
        if (! $quiz->setActive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'quiz' => $quiz,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Live!',
            'quiz' => $quiz,
        ];
    }

    public function close(Quiz $quiz)
    {
        if (! $quiz->setInactive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'quiz' => $quiz,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Closed!',
            'quiz' => $quiz,
        ];
    }

    public function evaluate(Quiz $quiz)
    {
        $scores = $quiz->participations->map->evaluate();

        return [
            'status' => 'success',
            'message' => 'All quiz responses have been evaluated',
            'scores' => $scores->toArray(),
        ];
    }
}
