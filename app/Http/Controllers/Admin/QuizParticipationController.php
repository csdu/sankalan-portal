<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Quiz;
use App\Models\QuizParticipation;
use App\Models\Team;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizParticipationController extends Controller
{
    public function index(Request $request, ?Quiz $quiz = null)
    {
        $query = QuizParticipation::withCount('responses');
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

    public function show(QuizParticipation $quizParticipation)
    {
        $quizParticipation->load(['team.members', 'responses.question.choices']);

        return view('admin.quizzes_teams.show', compact('quizParticipation'));
    }

    public function evaluate(QuizParticipation $quizParticipation)
    {
        $score = $quizParticipation->evaluate();

        return [
            'status' => 'success',
            'message' => 'Score has been evaluated.',
            'score' => $score,
        ];
    }
}
