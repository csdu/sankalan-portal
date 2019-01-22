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
        
        if(!array_key_exists('team_id', $requestData)) {
            $success = $event->participate();
        } else {
            $success = $event->participateAsTeam($requestData['team_id']);
        }

        if($success) {
            Session::flash('success', 'We have registered you for "'. $event->name .'" event!');
        }

        return redirect()->back();
    }
}
