<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            flash('Something went wrong')->error();
            return redirect()->back();
        }

        $token = strtoupper(Str::random(7));

        $quiz->update(['token' => $token]);

        flash('Quiz is now Live!')->success();
        return redirect()->back();
    }

    public function close(Quiz $quiz)
    {
        if (! $quiz->setInactive()) {
            flash('Something went wrong')->error();
            return redirect()->back();
        }

        $quiz->participations()->whereNull('finished_at')->update(['finished_at' => now()]);

        flash('Quiz is now Closed!')->success();
        return redirect()->back();
    }

    public function evaluate(Quiz $quiz)
    {
        $scores = $quiz->participations->map->evaluate();

        flash('All quiz responses have been evaluated')->success();

        return redirect()->route('admin.quizzes.teams.index', $quiz)->with('scores', $scores);
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
