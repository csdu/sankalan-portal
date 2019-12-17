@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">Edit Event</h2>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.events.update', $event) }}" method="POST">
            @csrf
            @method('PATCH')

            <label class="control">
                Title
            </label>
            <input class="control" name="title" type="text" placeholder="Sankalan" value="{{ old('title', $event->title) }}" required>
            <label class="control">
                Description
            </label>
            <textarea class="control" name="description" type="text" required>{{ old('description', $event->description) }}</textarea>
            <label class="control">
                Rounds
            </label>
            <input class="control" name="rounds" min="1" type="number" value="{{ old('rounds', $event->rounds) }}" required>
            <button class="btn is-blue" type="submit">Update</button>
            <a href="{{ route('admin.events.index') }}" class="btn">Cancel</a>
        </form>
    </div>
</div>
@endsection