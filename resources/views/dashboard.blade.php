@extends('layouts.app')
@section('content')
<div class="container mx-auto px-3 flex-1">
    {{-- <div class="card my-8">
        <h1 class="card-header">Dashboard</h1>

        <div class="card-content">
            @if (session('status'))
                <div class="bg-green-lighter text-green border border-green" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
    </div> --}}
    <div class="dashboard-grid mt-4 mb-12">
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
                    <ul class="list-reset flex mb-1 -mx-1">
                        @foreach($team->members as $member)
                        <li class="mx-2 bg-grey-light py-2 px-4 flex items-center rounded text-sm">
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
            <ul class="list-reset">
                @forelse($events as $event)
                <li class="px-6 py-3 hover:bg-grey-lighter">
                    <h3 class="text-base mb-3">{{ $event->title }}</h3>
                    <p class="mb-1">
                        Participating as team <b>{{ $event->team->name }}</b> @if(count($event->team->members)) with <em>{{ $event->team->members->first()->name }}</em>                    @endif
                    </p>
                    @if($event->ended_at != null)
                    <form action="{{ route('events.withdraw-part', $event) }}" method="POST">
                        @csrf @method('delete')
                        <button class="btn is-red is-sm">Withdraw</button>
                    </form>
                    @endif
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
