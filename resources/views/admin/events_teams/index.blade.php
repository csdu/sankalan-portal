@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between items-baseline">
            <div class="flex items-center">
                @if($event)
                <h2 class="text-xl font-normal">Teams - {{ ucwords($event->title) }} </h2>
                @else
                <h2 class="text-xl font-normal">Participations</h2>
                @endif
                <span class="ml-2 bg-blue-500 text-white rounded-full px-2 py-1 text-xs">{{ $events_teams->total() }}</span>
            </div>
            <get-routes class="flex items-center" select-classes="control is-sm" route-name="admin.events.teams.index" value="{{ $event ? $event->slug : '' }}">
                <option value="" selected>All Events</option>
                @foreach ($events as $eachEvent)
                <option value="{{ $eachEvent->slug}}">{{ ucwords($eachEvent->title) }}</option>
                @endforeach
                <template v-slot:button="{link}">
                    <a :href="link" class="btn is-blue is-sm ml-2 inline-flex items-center">Filter</a>
                </template>
            </get-routes>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-200">
                <th class="text-xs uppercase text-left pl-6 py-2">Team ID</th>
                <th class="text-xs uppercase text-left px-4 py-2">Team Name</th>
                <th class="text-xs uppercase text-left px-4 py-2">Members</th>
                @if(!$event)
                <th class="text-xs uppercase text-left px-4 py-2">Event Name</th>
                @endif
                <th class="text-xs uppercase text-left pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events_teams as $event_team)
            <tr class="border-t border-slate-200 hover:bg-slate-100">
                <td class="text-left pl-6 py-2 text-sm">{{ $event_team->team->uid }}</td>
                <td class="text-left px-4 py-2">{{ $event_team->team->name }}</td>
                <td class="text-left px-4 py-2">
                    <div class="flex -mx-1">
                        @foreach ($event_team->team->members as $member)
                        <span class="p-1 mx-1 bg-blue-500 text-white text-xs font-semibold rounded">{{ $member->first_name }}</span>
                        @endforeach
                    </div>
                </td>
                @if(!$event)
                <td class="text-left px-4 py-2 capitalize">{{ $event_team->event->title }}</td>
                @endif
                <td class="text-left pr-6 py-2 capitalize">
                    @if (!$event_team->disqualified)
                    <a href="{{ route('admin.events.teams.disqualify', $event_team->id) }}" onsubmit="return confirm('Are you sure about it.')" class="btn is-red is-sm font-normal">Disqualify</a>
                    @else
                    <a href="{{ route('admin.events.teams.undisqualify', $event_team->id) }}" onsubmit="return confirm('Are you sure about it.')" class="btn is-blue is-sm font-normal">Mercy</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        @if ($events_teams->hasPages())
            {{ $events_teams->links() }}
        @else
            <p class="text-center text-slate-600">That's all folks!</p>
        @endif
    </div>
</div>
@endsection
