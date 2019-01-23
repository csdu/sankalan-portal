@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="mb-16">
        <h1 class="text-center">Manage Teams</h1>
    </div>

    <div class="card mb-16">
        <h2 class="mb-4">Create a Team</h2>
        <form action="{{ route('teams') }}" method="POST">
            @csrf
            <div class="mb-4">
                {{-- <label for="users" class="block mb-2">Select Members: </label> --}}
                <input type="text" name="name" multiple class="block w-full px-2 py-1 bg-white text-black border" placeholder="Team Name">
            </div>
            <div class="mb-4">
                <label for="users" class="block mb-2">Select a Member: </label>
                <select id="users" type="text" name="member_id" class="block w-full px-2 py-1 bg-white text-black border">
                    <option value="">Individual</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->uid }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="p-2 text-xs uppercase tracking-wide font-semibold bg-blue hover:bg-blue-dark text-white">Create Team</button>
            </div>
        </form>
    </div>

    <h2 class="mb-6">Your Teams</h2>
    @forelse($teams as $team)
    <div class="card mb-4">
        <h3 class="mb-4 capitalize">{{ $team->name }}
        <small class="text-sm text-grey-darker ml-2">({{ $team->uid }})</small>
        </h3>
        <ul>
            @foreach($team->members as $member)
                <li class="{{ $member->id == Auth::id() ? 'text-grey-dark font-semibold' : '' }}">
                    {{ $member->id == Auth::id() ? 'You' : $member->name }}  
                    <span class="text-sm ml-2 font-normal">({{ $member->uid }})</span>
                </li>
            @endforeach
        </ul>
    </div>
    @empty
    <div class="card mb-4">
        <p> You have not created any teams yet.</p>
    </div>
    @endforelse
</div>
@endsection
