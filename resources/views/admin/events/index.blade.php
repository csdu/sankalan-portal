@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex">
            <h2 class="text-xl font-normal">Events</h2>
            <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $events->count() }}</span>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Event</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Rounds</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Participations</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Quizzes</th>
                <th class="text-xs uppercase font-light text-right pr-6 py-2">Actions</th>
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
                        <a v-if="event.active_quiz != null" href="#" class="btn is-green is-sm ml-1" v-text="event.active_quiz.title"></a>
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        <span v-if="event.rounds > 1" class="px-2 py-1 rounded-full bg-grey text-xs" v-text="event.rounds"></span>
                        <span v-else class="px-2 py-1 bg-blue text-white font-thin rounded text-xs">No Prelims</span>
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        <a v-if="event.teams_count" :href="route('admin.events.teams.index', event.slug)" class="link text-xs" v-text="`Participations (${event.teams_count})`"></a>
                        <span v-else class="text-xs text-red">No Participation</span>
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        <span v-if="event.quizzes_count" class="px-2 py-1 rounded-full bg-grey text-xs" v-text="event.quizzes_count"></span>
                        <span v-else class="text-xs text-red">No Quiz</span>
                    </td>
                    <td class="table-fit text-right pr-6 py-2">
                        <ajax-button v-if="event.isLive" @success="onComplete" @failure="onComplete"
                        :action="route('admin.events.end', event.slug)" method="POST" class="btn is-sm is-red">End</ajax-button>
                        <ajax-button v-else-if="!event.hasEnded" @success="onComplete" @failure="onComplete"
                        :action="route('admin.events.go-live', event.slug)" method="POST" class="btn is-sm is-green">Begin</ajax-button>
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