<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Session;
use Auth;
use App\Team;

class EventParticipationController extends Controller
{
    public function store(Event $event)
    {
        request()->validate([
            'team_id' => ['nullable', 'integer', 'exists:teams,id']
        ]);

        $user = Auth::user();

        if($teamId = request('team_id')) {
            $team = Team::find($teamId);
        } else {
            $team = $user->individualTeam ?? $user->createTeam($user->name);
        }
        
        $team->participate($event);

        flash("We have registered your team '{$team->uid}' for '{$event->name}' event!")->success();

        return redirect()->back();
    }
}
