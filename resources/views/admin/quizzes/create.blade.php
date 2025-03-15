@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')

<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">New Quiz</h2>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.quizzes.store') }}" method="POST" class="space-y-3">
            @csrf

            <div class="mb-2">
                <label class="control">
                    Title
                </label>
                <input class="control" name="title" type="text" placeholder="Sankalan" value="{{ old('title') }}" required>
            </div>
       
            <div class="mb-2">
                <label class="control">
                    Instructions
                </label>
                <textarea class="control" name="instructions" type="text" required>{{ old('instructions') }}</textarea>
            </div>
       
            <div class="mb-2">
                <label class="control">
                    Time limit <small>in seconds</small>
                </label>
                <input class="control" name="time_limit" type="number" value="1800" required>
            </div>
       
            <div class="mb-2">
                <label class="control">
                    Question limit
                </label>
                <input class="control" name="questions_limit" min="1" type="number" value="{{ old('questions_limit', 1) }}" required>
            </div>
       
            <div class="mb-2">
                <label class="control">
                    Event
                </label>
                <select class="control" name="event_id" required>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button class="btn is-blue" type="submit">Create</button>
                <a href="{{ route('admin.quizzes.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection