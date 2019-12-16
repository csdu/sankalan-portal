<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['teams', 'quizzes'])->with('activeQuiz')->get();

        return view('admin.events.index', compact('events'));
    }

    public function goLive(Event $event)
    {
        if (! $event->setLive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Event is live now!',
            'event' => $event,
        ]);
    }

    public function end(Event $event)
    {
        if (! $event->end()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Event has ended!',
            'event' => $event,
        ]);
    }

    public function add(Request $request)
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

    public function delete(Event $event)
    {
        // can only delete event which hasnt been started yet
        if ($event->started_at) {
            flash("You can only delete event which has'nt started yet", 'danger');

            return redirect()->back();
        } else {
            $event->delete();
            flash('Event deleted', 'success');
        }

        return redirect()->route('admin.events.index');
    }
}
