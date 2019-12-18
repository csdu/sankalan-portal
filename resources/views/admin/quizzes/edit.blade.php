@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">Edit Quiz</h2>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <label class="control">
                Title
            </label>
            <input class="control" name="title" type="text" value="{{ old('title', $quiz->title) }}" required>

            <label class="control">
                Instructions
            </label>
            <textarea class="control" name="instructions" type="text" required>{{ old('instructions', implode(" ", $quiz->instructions)) }}</textarea>

            <label class="control">
                Time limit <small>in seconds</small>
            </label>
            <input class="control" name="time_limit" type="number" value="{{ old('time_limit', $quiz->time_limit) }}" required>
            
            <label class="control">
                Question limit
            </label>
            <input class="control" name="questions_limit" min="1" type="number" value="{{ old('questions_limit', $quiz->questions_limit) }}" required>

            <label class="control">
                Event
            </label>
            <select class="control" name="event_id" required>
                @foreach ($events as $event)
                    @if ($event->id == $quiz->event_id)
                        <option value="{{ $event->id }}" selected>{{ $event->title }}</option>
                    @else
                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                    @endif
                @endforeach
            </select>

            <button class="btn is-blue" type="submit">Update</button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn">Cancel</a>
        </form>
    </div>
</div>

@endsection