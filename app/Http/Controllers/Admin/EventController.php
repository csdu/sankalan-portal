<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('admin.events.index', compact('events'));
    }
}
