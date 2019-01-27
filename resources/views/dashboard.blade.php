@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3">
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
    <div class="flex flex-wrap -mx-2">
        @foreach($events as $event)
        <div class="px-2 sm:w-1/2 mb-4">
            <div class="card seperated h-full flex flex-col">
                <h4 class="card-header capitalize flex items-center">
                    {{ $event->title }} 
                    @if($event->hasQuiz)
                        <span class="ml-2 p-1 text-xs uppercase bg-blue text-white rounded">Online</span> 
                    @endif
                </h4>
        
                <div class="card-content flex-1">
                    <p>{{ $event->description }}</p>
                </div>
        
                <div class="card-footer flex">
                    @if(!$participatingTeam = $event->participatingTeamByUser($signedInUser))
                        <form action="{{ route('events.participate', $event) }}" method="POST" class="flex items-center">
                            @csrf
                            <button type="submit" class="btn is-blue is-sm">
                                {{ count($signedInUser->teams) ? 'Participate' : 'Participate Alone' }}
                            </button>
                            @if(count($signedInUser->teams))
                                <span class="ml-3">as:</span> 
                                <select name="team_id" class="ml-1 control is-sm">
                                    @if(!$signedInUser->team_id)
                                        <option value="">Individual</option>
                                    @endif
                                    @foreach($signedInUser->teams as $team)
                                        <option value="{{ $team->id }}">{{$team->name}} - {{$team->uid}}{{ $team->id == $signedInUser->team_id ? ' (Individual)' : '' }}</option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-xs text-grey-dark ml-3">
                                    You have not created any teams yet, you can participate <em>alone</em> or <a href="{{ route('teams') }}" class="hover:underline text-blue">Create Team</a>.
                                    When you participate <em>alone</em>, a team will be created with a name <strong>"{{ $signedInUser->name }}"</strong>
                                </p>
                            @endif
                        </form>
                    @else 
                    <div class="flex-1 flex items-center">
                        <p class="flex-1">Participating as <strong class="text-xs">{{ $participatingTeam->name }} - {{ $participatingTeam->uid }}</strong>!</p>
                        <form action="{{ route('events.withdraw-part', $event) }}" method="POST">
                            @csrf @method('delete')
                            <button class="btn is-red is-sm">Withdraw</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
