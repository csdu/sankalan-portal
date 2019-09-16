<?php

namespace App\Http\Controllers;

use App\Models\EventParticipation;
use App\Models\Quiz;
use App\Models\QuizParticipation;
use App\Models\Team;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $teams = Team::whereHas('members', function($query){
            $query->where('users.id', auth()->id());
        })->with(['members'])->get();

        $event_participations = EventParticipation::with(['team', 'event.activeQuiz'])
            ->addSelect(['quizzes_count' => Quiz::selectRaw('COUNT(*)')->whereColumn('event_id', 'event_participations.event_id')])
            ->withActiveQuizParticipation()
            ->whereIn('team_id', $teams->map->id)
            ->get();

        return view('dashboard', compact('teams', 'event_participations'));
    }
}
