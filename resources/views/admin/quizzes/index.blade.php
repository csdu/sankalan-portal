@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">Quizzes</h2>
                <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $quizzes->count() }}</span>
            </div>
            <a href="{{ route('admin.quizzes.create') }}" class="btn is-sm is-blue">Add new quiz</a>
        </div>
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Quiz</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Event</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Time (mins)</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Participations</th>
                <th class="text-xs text-center uppercase font-light text-left px-4 py-2">Token</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Questions</th>
                <th class="text-xs uppercase font-light text-center pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
            <tr class="border-t hover:bg-grey-lighter" v-is="'quiz-row'" :data-quiz="{{$quiz}}" v-slot:default="{quiz, timeLimit, onComplete}">
                <td class="table-fit text-left pl-6 py-2 text-sm">
                    <span v-if="quiz.isActive" class="p-1 ml-1 rounded bg-green text-white font-extralight text-xs uppercase leading-none">LIVE</span>
                    <span v-else-if="quiz.isClosed" class="p-1 ml-1 rounded bg-red text-white font-extralight text-xs uppercase leading-none">Closed</span>
                    <span v-else class="p-1 ml-1 rounded bg-grey font-extralight text-xs uppercase leading-none">Offline</span>
                </td>
                <td class="text-left px-4 py-2">
                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="link" v-text="quiz.event.title + ' - ' + quiz.title"></a>
                </td>
                <td class="text-left px-4 py-2">
                    <span v-text="quiz.event.title" class="capitalize"></span>
                    <span v-if="quiz.event.isLive" class="p-1 ml-1 rounded bg-green text-white font-normal text-xs uppercase leading-none">LIVE</span>
                </td>
                <td class="table_fit text-left px-4 py-2" v-text="timeLimit"></td>
                <td class="table_fit text-center px-4 py-2">
                    <a v-if="quiz.participations_count" :href="route('admin.quizzes.teams.index', quiz.slug)" class="link text-xs" v-text="`Teams (${quiz.participations_count})`"></a>
                    <span v-else class="text-red text-xs">No Teams</span>
                </td>
                <td class="table_fit text-center px-4 py-2">
                    <span v-if="quiz.token" v-text="quiz.token"></span>
                    <span v-else class="text-red text-xs">No Token</span>
                </td>
                <td class="table_fit text-center px-4 py-2">
                    <span class="px-2 py-1 rounded-full bg-grey text-xs" v-text="quiz.questions_count"></span>
                </td>
                <td class="table-fit text-left pr-6 py-2">
                    <ajax-button v-if="quiz.isActive" class="btn is-red is-sm font-normal" :action="route('admin.quizzes.close', quiz.slug)" method="post" @success="onComplete" @failure="onComplete">
                        Close
                    </ajax-button>
                    <ajax-button v-else-if="!quiz.isClosed" class="btn is-green is-sm font-normal" :action="route('admin.quizzes.go-live', quiz.slug)" method="post" @success="onComplete" @failure="onComplete">
                        Go Live
                    </ajax-button>
                    <ajax-button v-if="quiz.isClosed" class="btn is-blue is-sm font-normal" :action="route('admin.quizzes.evaluate', quiz.slug)" method="post" @success="onComplete" @failure="onComplete">
                        Evaluate
                    </ajax-button>
                    <button v-else class="btn is-blue is-sm font-normal">
                        Evaluate
                    </button>
                    <div class="pt-2">
                        <form onsubmit="return confirm('Are you about deleting it.')" v-if="!quiz.isActive" class="inline-block" action="{{ route('admin.quizzes.delete', $quiz) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn is-sm is-red">Delete</button>
                        </form>
                        <a v-if="!quiz.isActive" class="btn is-sm is-yellow" href="{{ route('admin.quizzes.edit', $quiz) }}">Edit</a>
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
