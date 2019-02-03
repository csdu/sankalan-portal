@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="card seperated h-full">
        <div class="card-header">
            <div class="flex justify-between items-baseline">
                <div class="flex">
                    @if($event)
                        <h3 class="text-xl font-normal">Teams - {{ ucwords($event->title) }} </h3>
                    @else
                        <h3 class="text-xl font-normal">Participations</h3>
                    @endif
                    <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $events_teams->count() }}</span>
                </div>
                <get-routes class="flex" 
                    select-classes="control is-sm"
                    route-name="admin.events.teams.index" 
                    value="{{ $event ? $event->slug : '' }}">
                    <option value="" selected>All Events</option>
                    @foreach ($events as $eachEvent)
                        <option value="{{ $eachEvent->slug}}">{{ ucwords($eachEvent->title) }}</option>
                    @endforeach
                    <a slot="button" slot-scope="{link}" :href="link" class="btn is-blue is-sm ml-2 inline-flex items-center">Filter</a>
                </get-routes>
            </div>
        </div>
        <table class="w-full">
            <thead>
                <tr class="bg-grey-light">
                    <th class="text-xs uppercase font-thin text-left pl-6 py-2">Team ID</th>
                    <th class="text-xs uppercase font-thin text-left px-4 py-2">Team Name</th>
                    <th class="text-xs uppercase font-thin text-left px-4 py-2">Memebers</th>
                    @if(!$event)
                        <th class="text-xs uppercase font-thin text-left pl-4 py-2">Event Name</th>
                    @endif
                    <th class="text-xs uppercase font-thin text-left pl-6 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events_teams as $event_team)
                    <tr class="border-t hover:bg-grey-lighter" is="event-team-row" :data-event-team="{{$event_team}}">
                        <template slot-scope="{team, event, members, onComplete}">
                            <td class="table-fit text-left pl-6 py-2 text-sm" v-text="team.uid"></td>
                            <td class="text-left px-4 py-2" v-text="team.name"></td>
                            <td class="table-fit text-left px-4 py-2" v-text="members"></td>
                            @if(!$event)
                            <td class="text-left px-4-6 py-2 capitalize" v-text="event.title"></td>
                            @endif
                            <td class="table-fit text-right pr-6 py-2 capitalize">
                                <button class="btn is-red is-sm font-normal">Disqualify</button>
                            </td>
                        </template>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
@endsection