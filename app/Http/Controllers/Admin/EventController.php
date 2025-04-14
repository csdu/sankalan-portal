<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['teams', 'quizzes'])->with('activeQuiz')->get();

        return view('admin.events.index', compact('events'));
    }

    public function goLive(Event $event)
    {
        $success = $event->setLive();
        $status = $success ? 'success' : 'error';
        $message = $success ? 'Event is live now!' : 'Something went wrong';

        flash($message, $status);

        return redirect()->back();
    }

    public function end(Event $event)
    {
        $success = $event->end();
        $status = $success ? 'success' : 'error';
        $message = $success ? 'Event has ended!' : 'Something went wrong';

        flash($message, $status);

        return redirect()->back();
    }

    public function delete(Event $event)
    {
        // can only delete event which hasnt been started yet
        abort_if($event->started_at, 403, "You can only delete event which has'nt started yet");

        $event->delete();
        flash('Event deleted', 'success');

        return redirect()->route('admin.events.index');
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'description' => 'required|max:800',
            'rounds' => 'required|min:1',
        ]);

        Event::create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'description' => $request->description,
            'rounds' => $request->rounds,
        ]);

        flash('Event created!')->success();

        return redirect()->route('admin.events.index');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        request()->validate([
            'title' => 'required',
            'description' => 'required|max:800',
            'rounds' => 'required|min:1',
        ]);

        $event->update([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'description' => $request->description,
            'rounds' => $request->rounds,
        ]);

        flash('Event updated!')->success();

        return redirect()->route('admin.events.index');
    }
}
