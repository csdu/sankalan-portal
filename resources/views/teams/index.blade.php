@extends('layouts.app')

@section('content')
<div class="container mx-auto mb-16">
    <div class="my-8">
        <h2 class="text-center">Manage Teams</h2>
    </div>

    <div class="card mb-16">
        <h3 class="card-header">Create a Team</h3>
        <div class="card-content">
            <div class="inline-block min-w-1/2 px-4 py-3 mb-6 bg-blue-lightest text-blue-dark border border-blue-dark rounded">
                <h5 class="my-2 uppercase text-xs">Note</h5>
                <p>Please note that, your partner must be a <b>registered</b> user, before you can create team with them.</p>
            </div>
            <form action="{{ route('teams') }}" method="POST" class="flex flex-col sm:flex-row">
                @csrf
                <div class="mb-4 sm:flex-1 sm:mr-3 sm:mb-0">
                    <input type="text" name="name" class="control" placeholder="Team Name" required>
                </div>
                <div class="mb-4 sm:flex-1 sm:mr-3 sm:mb-0">
                    <input type="email" name="member_email" class="control" placeholder="Partner's Email (optional)">
                </div>
                <div>
                    <button type="submit" class="btn is-blue">Create Team</button>
                </div>
            </form>
        </div>
    </div>
    <h2 class="text-center mb-8">Your Teams</h2>
    @forelse($teams as $team)
    <div class="team-container mb-8">
        <h4 class="mb-4">
            {{ $team->name }}
            <small class="text-sm text-grey-darker ml-2">({{ $team->uid }})</small>
        </h4>
        <div class="team-details flex">
            <div class="card seperated flex-1 mr-4">
                <h4 class="card-header">Participations</h4>
                <div class="card-content">
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
            </div>
            <div class="card seperated flex-1">
                <h4 class="card-header">Members</h4>
                <div class="card-content">
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
        <p class="card-content"> You have not created any teams yet.</p>
    </div>
    @endforelse
</div>
@endsection
