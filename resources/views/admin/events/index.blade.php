@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated h-full">
    <div class="card-header">
        <h2 class="text-xl font-normal">Events</h2>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-thin text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Event</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Rounds</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Teams</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Quizzes</th>
                <th class="text-xs uppercase font-thin text-right pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr class="border-t hover:bg-grey-lighter" is="event-row" :data-event="{{ $event }}">
                <template slot-scope="{ event, onComplete }">
                    <td class="table-fit text-left pl-6 py-2">
                        <span v-if="event.isLive" class="p-1 uppercase text-xs rounded bg-green text-white">Live</span>
                        <span v-else-if="event.hasEnded" class="p-1 uppercase text-xs rounded bg-blue text-white">Ended</span>
                        <span v-else class="p-1 uppercase text-xs rounded bg-grey-light">not started</span>
                    </td>
                    <td class="text-left capitalize px-4 py-2">
                        <span v-text="event.title"></span>
                        <a v-if="event.active_quiz != null" href="#" class="btn is-green is-sm ml-1" v-text="'Active Quiz'"></a>
                    </td>
                    <td class="table-fit text-center px-4 py-2" v-text="event.rounds"></td>
                    <td class="table-fit text-center px-4 py-2">
                        <a v-if="event.teams_count" :href="route('admin.events.teams.index', event.slug)" class="link text-xs" v-text="`Teams (${event.teams_count})`"></a>
                        <span v-else class="text-xs text-red">No Teams</span>
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        <a v-if="event.quizzes_count" href="#" class="link text-xs" v-text="`Quizzes (${event.quizzes_count})`"></a>
                        <span v-else class="text-xs text-red">No Quiz</span>
                    </td>
                    <td class="table-fit text-right pr-6 py-2">
                        <ajax-button v-if="event.isLive" @success="onComplete" @failure="onComplete"
                        :action="route('events.end', event.slug)" method="POST" class="btn is-sm is-red">End</ajax-button>
                        <ajax-button v-else-if="!event.hasEnded" @success="onComplete" @failure="onComplete"
                        :action="route('events.go-live', event.slug)" method="POST" class="btn is-sm is-green">Begin</ajax-button>
                    </td>
                </template>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <p class="text-center text-grey">That's all folks!</p>
    </div>
</div>
@endsection