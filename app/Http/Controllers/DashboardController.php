<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $teams = auth()->user()->teams()->with(['events', 'members'])->get();

        $events = $teams->flatMap(function ($team) {
            return $team->events->map(function ($event) use ($team) {
                $event->team = $team;
                return $event;
            });
        });

        return view('dashboard', compact('teams', 'events'));
    }
}
