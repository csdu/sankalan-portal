<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EventParticipation;

class EventParticipationController extends Controller
{
    public function index(Request $request) {
        
        $query = EventParticipation::with(['team', 'event']);

        if($request->event) {
            $query = $query->where('event_id', $request->query('event'));
        }

        $participations = $query->get();
        
        return view('admin.participations.index', compact('participations'));
    }
}
