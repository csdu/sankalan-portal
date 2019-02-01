<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Event;
use Symfony\Component\HttpFoundation\Response;
use App\Quiz;

class QuizParticipationController extends Controller
{
    public function index(Quiz $quiz)
    {
        $participations = $quiz->participations()->with(['team', 'responses'])->get();

        return view('admin.quiz-participations.index')
            ->with(compact('participations'));
    }
    
    public function store(Event $event, Team $team)
    {
        if(!$event->allowActiveQuiz($team)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event doesn\'t have any active quiz!',
            ], Response::HTTP_BAD_REQUEST);
        }

        return [
            'status' => 'success',
            'message' => 'Team is now ready to take quiz!'
        ];
    }
}
