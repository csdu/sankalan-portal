<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Session;
use App\Team;

class EventParticipationController extends Controller
{
    public function store(Event $event)
    {
        $requestData = request()->validate([
            'team_id' => ['sometimes', 'integer', 'exists:teams,id']
        ]);
        
        $team_id = $requestData['team_id'] ?? 
            optional(auth()->user()->individualTeam)->id ?? 
            auth()->user()->createTeam(auth()->user()->name)->id;

        $event->participate($team_id);

        Session::flash('success', 'We have registered you for "'. $event->name .'" event!');

        return redirect()->back();
    }
}
