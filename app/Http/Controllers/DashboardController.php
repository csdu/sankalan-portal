<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::with('teams.members')->get();
        return view('dashboard', compact('events'));
    }
}
