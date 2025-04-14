<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\Team;
use Illuminate\Http\Request;

class QuizResponseController extends Controller
{
    public function index(Request $request, ?Quiz $quiz = null)
    {
        $query = QuizResponse::withCount('responses');
        $quizzes = Quiz::with('event')->get();

        if ($quiz) {
            $query->whereHas(
                'quiz',
                fn ($query) => $query->where('slug', $quiz->slug)
            );
        }

        $query->with([
            'team.members',
            'quiz' => fn ($query) => $query->withCount('questions'),
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
            flash('Event doesn\'t have any active quiz!')->error();

            return redirect()->back();
        }

        $event->activeQuiz->allowTeam($team);

        flash('Team is now ready to take quiz!')->success();

        return redirect()->back();
    }

    public function show(QuizResponse $quizResponse)
    {
        $quizResponse->load(['team.members', 'responses.question.choices']);

        return view('admin.quizzes_teams.show', compact('quizResponse'));
    }

    public function evaluate(QuizResponse $quizResponse)
    {
        $quizResponse->evaluate();

        flash('Score has been evaluated.')->success();

        return redirect()->back();
    }

    public function showExtraTimeForm(QuizResponse $quizResponse)
    {
        return view('admin.quizzes_teams.extra-time', [
            'quiz_team' => $quizResponse,
        ]);
    }

    public function storeExtraTime(Request $request, QuizResponse $quizResponse)
    {
        $quizResponse->update([
            'started_at' => now()->addMinutes($request->time),
            'finished_at' => null,
        ]);

        flash('Time updated!')->success();

        return redirect()->route('admin.quizzes.teams.index');
    }
}
