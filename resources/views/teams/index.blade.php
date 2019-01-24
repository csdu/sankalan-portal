@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="my-8">
        <h1 class="text-center">Manage Teams</h1>
    </div>

    <div class="card mb-16">
        <h2 class="card-header">Create a Team</h2>
        <form action="{{ route('teams') }}" method="POST" class="card-content">
            @csrf
            <div class="mb-4">
                <input type="text" name="name" multiple class="control" placeholder="Team Name">
            </div>
            <div class="mb-4">
                <label for="users" class="control">Select Member</label>
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
            <div class="flex">
                <div class="flex-1 px-2">
                    <h4 class="mb-2">Participations</h4>
                    <ul class="list-reset">
                        @forelse($team->events as $event)
                        <li class="py-1 px-2 hover:bg-grey-lighter">
                            <div class="flex justify-between items-center">
                                <span class="capitalize mr-2">{{ $event->title }}</span>
                                <form action="{{ route('events.withdraw-part', $event) }}" method="POST">
                                    @csrf @method('delete')
                                    <button class="btn is-red is-sm">Withdraw</button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li>You have not participated in any event.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="flex-1 px-4">
                    <h4 class="mb-2">Members</h4>
                    <ul class="list-reset">
                        @foreach($team->members as $member)
                            <li class="py-1{{ $member->id == Auth::id() ? 'text-grey-dark font-semibold' : '' }}">
                                <div class="flex items-center">
                                <img class="w-6 h-6 rounded mr-2" src="https://www.gravatar.com/avatar/{{ $member->emailHash }}?s=50&d=retro" alt="">
                                    <p>
                                        {{ $member->id == Auth::id() ? 'You' : $member->name }}  
                                        <span class="text-sm ml-2 font-normal">({{ $member->uid }})</span>
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card mb-4">
        <p> You have not created any teams yet.</p>
    </div>
    @endforelse
</div>
@endsection
