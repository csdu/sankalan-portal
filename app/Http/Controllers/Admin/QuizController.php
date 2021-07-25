<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withCount(['participations', 'questions'])
            ->with(['event'])
            ->get();

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function goLive(Quiz $quiz)
    {
        if (! $quiz->setActive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'quiz' => $quiz,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $token = strtoupper(Str::random(7));

        $quiz->update(['token' => $token]);

        return [
            'status' => 'success',
            'message' => 'Quiz is now Live!',
            'quiz' => $quiz,
        ];
    }

    public function close(Quiz $quiz)
    {
        if (! $quiz->setInactive()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'quiz' => $quiz,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

		$unFinishedQuizResponses = $quiz->participations()->where('finished_at', null)->get();

        foreach ($unFinishedQuizResponses as $quizResponse) {
            $quizResponse->update([
				'finished_at' => now()
			]);
        }

        return [
            'status' => 'success',
            'message' => 'Quiz is now Closed!',
            'quiz' => $quiz,
        ];
    }

    public function evaluate(Quiz $quiz)
    {
        $scores = $quiz->participations->map->evaluate();

        return [
            'status' => 'success',
            'message' => 'All quiz responses have been evaluated',
            'scores' => $scores->toArray(),
        ];
    }

    public function create()
    {
        $events = Event::all();

        return view('admin.quizzes.create')->withEvents($events);
    }

    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'instructions' => 'required',
            'time_limit' => 'numeric',
            'questions_limit' => 'numeric',
            'event_id' => 'exists:events,id',
        ]);

        Quiz::create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'instructions' => $request->instructions,
            'time_limit' => $request->time_limit,
            'questions_limit' => $request->questions_limit,
            'event_id' => $request->event_id,
        ]);

        flash('Quiz created!')->success();

        return redirect()->route('admin.quizzes.index');
    }

    public function edit(Quiz $quiz)
    {
        $events = Event::all();

        return view('admin.quizzes.edit', compact(['quiz', 'events']));
    }

    public function update(Request $request, Quiz $quiz)
    {
        request()->validate([
            'title' => 'required',
            'instructions' => 'required',
            'time_limit' => 'numeric',
            'questions_limit' => 'numeric',
            'event_id' => 'exists:events,id',
        ]);

        $quiz->update([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'instructions' => $request->instructions,
            'time_limit' => $request->time_limit,
            'questions_limit' => $request->questions_limit,
            'event_id' => $request->event_id,
        ]);

        flash('Quiz updated!')->success();

        return redirect()->route('admin.quizzes.index');
    }

    public function delete(Quiz $quiz)
    {
        // can only delete quiz which is not active
        abort_if($quiz->isActive, 403, "You can only delete quiz which isn't active");

        $quiz->delete();
        flash('Quiz deleted', 'success');

        return redirect()->route('admin.quizzes.index');
    }

    public function show(Quiz $quiz)
    {
        return view('admin.quizzes.show', compact('quiz'));
    }
}
