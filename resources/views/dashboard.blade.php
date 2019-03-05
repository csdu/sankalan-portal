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
                <li class="py-3 px-6 hover:bg-grey-lighter">
                    <h3 class="text-base mb-3">{{ ucwords(strtolower($team->name)) }}</h3>
                    <ul class="list-reset flex flex-wrap mb-1 -mx-2 my-1">
                        @foreach($team->members as $member)
                        <li class="mx-2 my-1 bg-grey-light py-2 px-4 flex items-center rounded text-sm whitespace-no-wrap">
                            <img src="https://gravatar.com/avatar/{{ $member->emailHash }}?s=50&d=retro" alt="" class="w-4 h-4 rounded-full mr-1">
                            <span>{{ ucwords(strtolower($member->name)) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </li>
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
                @forelse($events as $event)
                <li class="px-6 py-3 hover:bg-grey-lighter" style="order: {{$event->isLive ? 0 : ($event->hasEnded ? 3 : 1)}};">
                    <div class="content flex-1 flex flex-col">
                        <h3 class="text-lg mb-1 flex items-center">
                            <span>{{ $event->title }}</span> @if($event->hasStarted || $event->hasEnded)
                            <span class="ml-1 w-2 h-2 inline-block rounded-full {{ $event->isLive ? 'bg-green-light' : 'bg-red' }}"></span>                        @endif
                        </h3>
                        <p class="mb-1">
                            <b class="mr-1">Team:</b>
                            <span class="underline">{{ ucwords(strtolower($event->team->name)) }}</span>
                        </p>
                        @if($event->activeQuiz)
                        <p class="text-grey-darker my-1">
                            <b>{{ $event->activeQuiz->title }}</b> is live.
                        </p>
                        @elseif($event->isLive && $event->quizzes->count())
                        <p class="text-grey-darker font-semibold my-1">
                            Quiz will start soon
                        </p>
                        @endif
                        <div class="mt-auto flex-items-center">
                            @if($event->canBeWithdrawn($event->team))
                            <form action="{{ route('events.withdraw-part', $event) }}" method="POST" class="inline-block mr-2">
                                @csrf @method('delete')
                                <button class="btn is-red p-1 text-xs">Withdraw</button>
                            </form>
                            @endif @if($event->isLive && $event->quizzes->count() && $event->activeQuiz) @if($event->activeQuiz->hasTeamResponded($event->team))
                            <span class="btn p-1 text-xs cursor-not-allowed">Quiz Taken</span> @elseif($event->activeQuiz->isTeamAllowed($event->team))
                            <a href="{{ route('quizzes.show', $event->activeQuiz) }}" class="btn is-green p-1 text-xs">Take Quiz</a>                        @else
                            <span class="text-red">You are not allowed to take quiz, yet.</span> @endif @endif
                        </div>
                    </div>
                </li>
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
