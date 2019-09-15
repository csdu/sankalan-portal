<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
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
}
