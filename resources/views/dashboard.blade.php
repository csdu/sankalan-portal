@extends('layouts.app')
@section('content')
<div class="container mx-auto px-3 flex-1">
    <div class="dashboard-grid mt-4 mb-12">
        
        <div class="card create-team">
            <h3 class="card-header text-xl">Create a Team</h3>
            <form action="{{ route('teams') }}" method="POST" class="card-content">
                <div class="inline-block min-w-1/2 px-4 py-3 mb-6 bg-blue-lightest text-blue-dark border border-blue-dark rounded">
                    <h5 class="my-2 uppercase text-xs">Note</h5>
                    <p>Please note that, your partner must be a <b>registered</b> user, before you can create team with them.</p>
                </div>
                @csrf
                <div class="mb-2">
                    <input type="text" name="name" class="control" placeholder="Team Name" required>
                </div>
                <div class="mb-2">
                    <input type="email" name="member_email" class="control" placeholder="Partner's Email (optional)">
                </div>
                <div>
                    <button type="submit" class="btn is-blue">Create Team</button>
                </div>
            </form>
        </div>
        
        <div class="card seperated team-card">
            <div class="card-header">
                <h2 class="text-xl">
                    <a href="{{ route('teams') }}">Teams</a>
                </h2>
            </div>
            <ul class="list-reset">
                @forelse($teams as $team)
                    @include('partials.user-dashboard.team')
                @empty
                <li class="py-4 px-6 text-center">
                    <p class="text-grey-dark">
                        You don't have any teams yet. please
                        <a href="{{ route('teams') }}" class="link font-bold">create a team</a>
                        to participate
                    </p>
                </li>
                @endforelse
            </ul>
        </div>
        {{-- end of teams card --}}
        
        <div class="card seperated participation-card">
            <div class="card-header flex items-center justify-between">
                <h2 class="text-xl">
                    Participations
                </h2>
            </div>
            <ul class="list-reset flex flex-col">
                @forelse($event_participations as $event_participation)
                    @include('partials.user-dashboard.event', [
                        'quizzes_count' => $event_participation->quizzes_count,
                        'event' => $event_participation->event,
                        'team' => $event_participation->team,
                        'quizParticipation' => $event_participation->activeQuizParticipation,
                    ])
                @empty
                <li class="px-6 py-4">
                    <p class="text-grey-dark text-center">
                        You have not particpated in any event yet. See all
                        <a href="{{ route('events.index') }}" class="link font-bold">events</a>
                    </p>
                </li>
                @endforelse
            </ul>
        </div>
        {{-- end of participations card --}}

    </div>
</div>
@endsection
