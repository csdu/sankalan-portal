<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\Team;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizResponseController extends Controller
{
    public function index(Request $request, ?Quiz $quiz = null)
    {
        $query = QuizResponse::withCount('responses');
        $quizzes = Quiz::with('event')->get();

        if ($quiz) {
            $query->whereHas('quiz', function ($query) use ($quiz) {
                $query->where('slug', $quiz->slug);
            });
        }

        $query->with([
            'team.members',
            'quiz' => function ($query) {
                $query->withCount('questions');
            },
        ]);

        if ($request->has('top_scorers')) {
            $quizzes_teams = $query->orderBy('score', 'desc')
                ->paginate($request->query('top_scorers'))
                ->appends(['top_scorers' => $request->query('top_scorers')]);
        } else {
            $quizzes_teams = $query->paginate(config('app.pagination.perPage'));
        }

        return view('admin.quizzes_teams.index')
            ->with(compact('quizzes_teams', 'quiz', 'quizzes'));
    }

    public function store(Event $event, Team $team)
    {
        if (! $event->active_quiz_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event doesn\'t have any active quiz!',
            ], Response::HTTP_BAD_REQUEST);
        }

        $event->activeQuiz->allowTeam($team);

        return [
            'status' => 'success',
            'message' => 'Team is now ready to take quiz!',
        ];
    }

    public function show(QuizResponse $quizResponse)
    {
        $quizResponse->load(['team.members', 'responses.question.choices']);

        return view('admin.quizzes_teams.show', compact('quizResponse'));
    }

    public function evaluate(QuizResponse $quizResponse)
    {
        $score = $quizResponse->evaluate();

        return [
            'status' => 'success',
            'message' => 'Score has been evaluated.',
            'score' => $score,
        ];
    }
}
