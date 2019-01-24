@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-16">
        <h1 class="text-center">Manage Teams</h1>
    </div>

    <div class="card mb-16">
        <h2 class="card-header">Create a Team</h2>
        <form action="{{ route('teams') }}" method="POST" class="card-content">
            @csrf
            <div class="mb-4">
                {{-- <label for="users" class="block mb-2">Select Members: </label> --}}
                <input type="text" name="name" multiple class="control" placeholder="Team Name">
            </div>
            <div class="mb-4">
                <label for="users" class="block mb-2">Select a Member: </label>
                <select id="users" type="text" name="member_id" class="control">
                    <option value="">Individual</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->uid }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn is-blue">Create Team</button>
            </div>
        </form>
    </div>

    <h2 class="mb-6">Your Teams</h2>
    @forelse($teams as $team)
    <div class="card seperated mb-4">
        <h4 class="card-header">
            {{ $team->name }}
            <small class="text-sm text-grey-darker ml-2">({{ $team->uid }})</small>
        </h4>
        <div class="card-content">
            <ul class="list-reset">
                @foreach($team->members as $member)
                    <li class="{{ $member->id == Auth::id() ? 'text-grey-dark font-semibold' : '' }}">
                        {{ $member->id == Auth::id() ? 'You' : $member->name }}  
                        <span class="text-sm ml-2 font-normal">({{ $member->uid }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @empty
    <div class="card mb-4">
        <p> You have not created any teams yet.</p>
    </div>
    @endforelse
</div>
@endsection
