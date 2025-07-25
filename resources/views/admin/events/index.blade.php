@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex items-center">
                <h2 class="text-xl font-normal">Events</h2>
                <span class="ml-2 bg-blue-500 text-white rounded-full px-2 py-1 text-xs">{{ $events->count() }}</span>
            </div>
            <a href="{{ route('admin.events.create') }}" class="btn is-sm is-blue">Add new</a>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-200">
                <th class="text-xs uppercase text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase text-left px-4 py-2">Event</th>
                <th class="text-xs uppercase text-left px-4 py-2">Rounds</th>
                <th class="text-xs uppercase text-left px-4 py-2">Participations</th>
                <th class="text-xs uppercase text-left px-4 py-2">Quizzes</th>
                <th class="text-xs uppercase text-right pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr class="border-t border-slate-200 hover:bg-slate-100">
                    <td class="table-fit text-left pl-6 py-2">
                        @if ($event->is_live)
                            <span class="p-1 uppercase text-xs rounded bg-emerald-500 text-white">Live</span>
                        @elseif($event->ended_at)
                            <span class="p-1 uppercase text-xs rounded bg-blue-500 text-white">Ended</span>
                        @else
                            <span class="p-1 uppercase text-xs rounded bg-slate-200">not started</span>
                        @endif
                    </td>
                    <td class="text-left capitalize px-4 py-2">
                        <span>{{ $event->title }}</span>
                        @if($event->active_quiz)
                            <a href="#" class="btn is-green is-sm ml-1">{{ $event->active_quiz->title }}</a>
                        @endif
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        @if($event->rounds > 1)
                            <span class="px-2 py-1 rounded-full bg-slate-200 text-xs">{{ $event->rounds }}</span>
                        @else
                            <span class="px-2 py-1 bg-blue-500 text-white font-extralight rounded text-xs">No Prelims</span>
                        @endif
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        @if ($event->teams_count)
                            <a href="{{ route('admin.events.teams.index', $event) }}" class="link text-xs">Participations ({{ $event->teams_count }})</a>
                        @else
                            <span class="text-xs text-red-500">No Participation</span>
                        @endif
                    </td>
                    <td class="table-fit text-center px-4 py-2">
                        @if ($event->quizzes_count)
                            <span class="px-2 py-1 rounded-full bg-slate-200 text-xs">{{ $event->quizzes_count }}</span>
                        @else
                            <span class="text-xs text-red-500">No Quiz</span>
                        @endif
                    </td>
                    <td class="text-right pr-6 py-2">
                        <div class="flex justify-end space-x-2 flex-wrap">
                            @if($event->is_live)
                                <form action="{{ route('admin.events.end', $event) }}" method="POST">
                                    @csrf
                                    <button class="btn is-sm is-red">End</button>
                                </form>
                            @elseif(!$event->ended_at)
                                <form action="{{ route('admin.events.go-live', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn is-sm is-green">Begin</button>
                                </form>
                            @endif
                            @if (!$event->is_live)
                            <form onsubmit="return confirm('Are you about deleting it.')" class="inline-block" action="{{ route('admin.events.delete', $event) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn is-sm is-red">Delete</button>
                            </form>
                            <a href="{{ route('admin.events.teams.index', $event) }}" class="btn is-sm is-blue">View</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <p class="text-center text-slate-500">That's all folks!</p>
    </div>
</div>
@endsection
