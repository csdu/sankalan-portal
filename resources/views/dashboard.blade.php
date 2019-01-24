@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="card my-8">
        <h1 class="card-header">Dashboard</h1>

        <div class="card-content">
            @if (session('status'))
                <div class="bg-green-lighter text-green border border-green" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
    </div>

    <h2 class="mb-6">Events</h2>
    @foreach($events as $event)
    <div class="card seperated mb-4">
        <h4 class="card-header capitalize flex items-center">
            {{ $event->title }} 
            @if($event->hasQuiz)
                <span class="ml-2 p-1 text-xs uppercase bg-blue text-white rounded">Online</span> 
            @endif
        </h4>

        <div class="card-content">
            <p>{{ $event->description }}</p>
        </div>

        <div class="card-footer flex">
            @if(!$event->isParticipating($signedInUser))
                <form action="{{ route('events.participate', $event) }}" method="POST" class="flex items-center">
                    @csrf
                    <button type="submit" class="btn is-blue is-sm">
                        {{ count($signedInUser->teams) ? 'Participate' : 'Participate as single-person Team' }}
                    </button>
                    @if(count($signedInUser->teams))
                        <span class="ml-3">as:</span> 
                        <select name="member_id" class="ml-1 control is-sm">
                            @if(!$signedInUser->team_id)
                                <option value="">Individual</option>
                            @endif
                            @foreach($signedInUser->teams as $team)
                                <option value="{{ $team->id }}">{{$team->name}} - {{$team->uid}}{{ $team->id == $signedInUser->team_id ? ' (Individual)' : '' }}</option>
                            @endforeach
                        </select>
                    @else
                        <p>You have not created any teams yet, you can participate as <em>single-person</em> Team or <a href="{{ route('teams') }}" class="hover:underline text-blue">create team</a> </p>
                    @endif
                </form>
            @else 
                <p>You are participating in this event!</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
