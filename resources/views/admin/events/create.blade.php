@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">New Event</h2>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="control">
                    Title
                </label>
                <input class="control" name="title" type="text" placeholder="Sankalan" value="{{ old('title') }}" required>
            </div>
            <div>
                <label class="control">
                    Description
                </label>
                <textarea class="control" name="description" type="text" required>
                    {{ old('description') }}
                </textarea>
            </div>
            <div>
                <label class="control">
                    Rounds
                </label>
                <input class="control" name="rounds" min="1" type="number" value="{{ old('rounds', 1) }}" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button class="btn is-blue" type="submit">Create</button>
                <a href="{{ route('admin.events.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
