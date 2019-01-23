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
        
        if($team->participate($event)) {
            flash("We have registered your team '{$team->uid}' for '{$event->title}' event!")->success();
        } else {
            flash('We do not allow same person to participate in same event twice, not even as a different team')->error();
        }

        return redirect()->back();
    }
}
