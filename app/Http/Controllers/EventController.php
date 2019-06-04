<?php

namespace App\Http\Controllers;

use App\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['teams.members'])->get();

        return view('events.index', ['events' => $events]);
    }
}
