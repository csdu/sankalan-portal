<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipation;

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

    public function disqualify($eventParticipationId)
    {
        $eventParticipation = EventParticipation::findOrFail($eventParticipationId);
        $eventParticipation->setDisqualify();

        flash('Team disqualified!');

        return redirect()->back();
    }

    public function undisqualify($eventParticipationId)
    {
        $eventParticipation = EventParticipation::findOrFail($eventParticipationId);
        $eventParticipation->revertDisqualify();

        flash('Team undisqualified!');

        return redirect()->back();
    }
}
