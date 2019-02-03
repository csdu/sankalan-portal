@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated h-full">
    <div class="card-header">
        <div class="flex">
            <h2 class="text-xl font-normal">Quizzes</h2>
            <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $quizzes->count() }}</span>
        </div>
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-thin text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Quiz</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Event</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Time (mins)</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Participations</th>
                <th class="text-xs uppercase font-thin text-left px-4 py-2">Questions</th>
                <th class="text-xs uppercase font-thin text-left pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
                <tr class="border-t hover:bg-grey-lighter" is="quiz-row" :data-quiz="{{$quiz}}">
                    <template slot-scope="{quiz, timeLimit, onComplete}">
                        <td class="table-fit text-left pl-6 py-2 text-sm">
                            <span v-if="quiz.isActive" class="p-1 ml-1 rounded bg-green text-white font-thin text-xs uppercase leading-none">LIVE</span>
                            <span v-else-if="quiz.isClosed" class="p-1 ml-1 rounded bg-red text-white font-thin text-xs uppercase leading-none">Closed</span>
                            <span v-else class="p-1 ml-1 rounded bg-grey font-thin text-xs uppercase leading-none">Offline</span>
                        </td>
                        <td class="text-left px-4 py-2">
                            <a href="#" class="link" v-text="quiz.title"></a>
                        </td>
                        <td class="table-fit text-left px-4 py-2">
                            <span v-text="quiz.event.title" class="capitalize"></span>
                            <span v-if="quiz.event.isLive" class="p-1 ml-1 rounded bg-green text-white font-normal text-xs uppercase leading-none">LIVE</span>
                        </td>
                        <td class="table_fit text-left px-4 py-2" v-text="timeLimit"></td>
                        <td class="table_fit text-center px-4 py-2" >
                            <a v-if="quiz.participations_count" 
                            :href="route('admin.quizzes.teams.index', quiz.slug)" 
                            class="link text-xs" 
                            v-text="`Teams (${quiz.participations_count})`"></a>
                            <span v-else class="text-red text-xs">No Teams</span>
                        </td>
                        <td class="table_fit text-center px-4 py-2">
                            <span class="px-2 py-1 rounded-full bg-grey text-xs" v-text="quiz.questions_count"></span>
                        </td>
                        <td class="table-fit text-right pr-6 py-2">
                            <ajax-button v-if="quiz.isActive" 
                                class="btn is-red is-sm font-normal"
                                :action="route('admin.quizzes.close', quiz.slug)" method="post"
                                @success="onComplete" @failure="onComplete">
                                Close
                            </ajax-button>
                            <ajax-button v-else-if="!quiz.isClosed" 
                                class="btn is-green is-sm font-normal"
                                :action="route('admin.quizzes.go-live', quiz.slug)" method="post"
                                @success="onComplete" @failure="onComplete">
                                Go Live
                            </ajax-button>
                            <button v-else
                                class="btn is-blue is-sm font-normal">
                                Evaluate
                            </button>
                        </td>
                    </template>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <p class="text-center text-grey-dark">That's all folks!</p>
    </div>
</div>
@endsection