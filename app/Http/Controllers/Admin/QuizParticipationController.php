<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use App\Event;
use Symfony\Component\HttpFoundation\Response;
use App\Quiz;
use App\QuizParticipation;

class QuizParticipationController extends Controller
{
    public function index(Quiz $quiz = null)
    {
        
        $query = QuizParticipation::withCount('responses');
        $quizzes = Quiz::select(['slug', 'title'])->get();
        if($quiz) {
            $query->whereHas('quiz', function($query) use ($quiz) {
                $query->where('slug', $quiz->slug);
            });
        }

        $quizzes_teams = $query->with(['team',
            'quiz' => function($query) {
                $query->withCount('questions');
            }
        ])->paginate(15);

        return view('admin.quizzes_teams.index')
            ->with(compact('quizzes_teams', 'quiz', 'quizzes'));
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

    public function evaluate(QuizParticipation $quizParticipation) {
        $score = $quizParticipation->evaluate();

        return [
            'status' => 'success',
            'message' => 'Score has been evaluated.',
            'score' => $score
        ];
    }
}
