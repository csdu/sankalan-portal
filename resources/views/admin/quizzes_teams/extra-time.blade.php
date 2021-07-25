@extends('layouts.admin')
@section('title', 'Quiz Participation')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">
                    Extra Time to <small class="text-blue">{{ $quiz_team->team->name }}</small>
                    for <small class="text-blue">{{ $quiz_team->quiz->title }}</small>
                </h2>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.quizzes.teams.extra-time', $quiz_team) }}" method="post">
            @csrf
            <div class="">
                <label class="control">
                    Extra Time <small>(in minutes)</small>
                </label>
                <input class="control" name="time" type="number" min="1" value="{{ old('time', 5) }}" required>
            </div>

            <button class="btn is-green mt-4" type="submit">Save</button>
            <a href="{{ route('admin.quizzes.teams.index') }}" class="btn">Cancel</a>
        </form>
    </div>
</div>
@endsection