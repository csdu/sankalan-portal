<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventParticipation;
use App\Http\Controllers\Controller;

class EventTeamController extends Controller
{
    public function index(?Event $event = null)
    {
        $events = Event::select(['slug', 'title'])->get();

        $query = EventParticipation::query();

        if ($event) {
            $query = $query->whereHas('event', function ($query) use ($event) {
                $query->where('events.slug', $event->slug);
            });
        }

        $events_teams = $query->with(['event', 'team.members'])->paginate(config('app.pagination.perPage'));
        return view('admin.events_teams.index', compact('events_teams', 'events', 'event'));
    }
}
