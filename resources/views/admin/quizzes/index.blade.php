@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex items-center">
                <h2 class="text-xl font-normal">Quizzes</h2>
                <span class="ml-2 bg-blue text-white rounded-full px-2 py-1 leading-0 text-xs">{{ $quizzes->count() }}</span>
            </div>
            <a href="{{ route('admin.quizzes.create') }}" class="btn is-sm is-blue">Add new quiz</a>
        </div>
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-grey-light">
                <th class="table-fit text-xs uppercase font-light text-left pl-6 py-2">Status</th>
                <th class="table-fit text-xs uppercase font-light text-left px-4 py-2">Quiz</th>
                <th class="table-fit text-xs uppercase font-light text-left px-4 py-2">Event</th>
                <th class="table-fit text-xs uppercase font-light text-left px-4 py-2">Time (mins)</th>
                <th class="table-fit text-xs uppercase font-light text-left px-4 py-2">Participations</th>
                <th class="table-fit text-xs uppercase font-light text-left px-4 py-2">Token</th>
                <th class="table-fit text-xs uppercase font-light text-left px-4 py-2">Questions</th>
                <th class="text-xs uppercase font-light text-right pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
            <tr class="border-t hover:bg-grey-lighter">
                <td class="table-fit text-left pl-6 py-2 text-sm">
                    @if ($quiz->isLive)
                        <span class="p-1 ml-1 rounded bg-green text-white font-extralight text-xs uppercase leading-none">LIVE</span>
                    @elseif($quiz->isClosed)
                        <span class="p-1 ml-1 rounded bg-red text-white font-extralight text-xs uppercase leading-none">Closed</span>
                    @else
                        <span class="p-1 ml-1 rounded bg-grey font-extralight text-xs uppercase leading-none">Offline</span>
                    @endif
                </td>
                <td class="text-left px-4 py-2">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="link">{{ $quiz->event->title . ' - ' . $quiz->title }}</a>
                </td>
                <td class="text-left px-4 py-2">
                    <span class="capitalize">{{ $quiz->event->title }}</span>
                    @if($quiz->event->isLive)
                        <span class="p-1 ml-1 rounded bg-green text-white font-normal text-xs uppercase leading-none">LIVE</span>
                    @endif
                </td>
                <td class="table-fit text-left px-4 py-2">{{ $quiz->timeLimit }}</td>
                <td class="table-fit text-center px-4 py-2">
                    @if($quiz->participations_count)
                        <a href="{{ route('admin.quizzes.teams.index', $quiz->slug) }}" class="link text-xs">Teams ({{ $quiz->participations_count }})</a>
                    @else
                        <span class="text-red text-xs">No Teams</span>
                    @endif
                </td>
                <td class="table-fit text-center px-4 py-2">
                    @if($quiz->token)
                        <span>{{ $quiz->token }}</span>
                    @else
                        <span class="text-red text-xs">No Token</span>
                    @endif
                </td>
                <td class="table-fit text-center px-4 py-2">
                    <span class="px-2 py-1 rounded-full bg-grey text-xs">{{ $quiz->questions_count }}</span>
                </td>
                <td class="table-fit text-left pr-6 py-2">
                    <div class="flex flex-wrap justify-end gap-2">
                        @if($quiz->isActive)
                            <ajax-button class="btn is-red is-sm font-normal" action="{{ route('admin.quizzes.close', $quiz->slug) }}" method="post">
                                Close
                            </ajax-button>
                        @elseif(!$quiz->isClosed)
                            <ajax-button class="btn is-green is-sm font-normal" action="{{ route('admin.quizzes.go-live', $quiz->slug) }}" method="post">
                                Go Live
                            </ajax-button>
                        @endif
                        @if($quiz->isClosed)
                            <ajax-button class="btn is-blue is-sm font-normal" action="{{ route('admin.quizzes.evaluate', $quiz->slug) }}" method="post">
                                Evaluate
                            </ajax-button>
                        @else
                            <button class="btn is-blue is-sm font-normal">
                                Evaluate
                            </button>
                        @endif
                        @if(!$quiz->is_active)
                            <form onsubmit="return confirm('Are you about deleting it.')" class="inline-block" action="{{ route('admin.quizzes.delete', $quiz) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn is-sm is-red">Delete</button>
                            </form>
                            <a class="btn is-sm is-yellow" href="{{ route('admin.quizzes.edit', $quiz) }}">Edit</a>
                        @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <p class="text-center text-grey-dark">That's all folks!</p>
    </div>
</div>
@endsection
