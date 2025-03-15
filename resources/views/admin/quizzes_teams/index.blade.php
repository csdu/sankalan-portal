@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between items-center">
            <div class="flex-none flex items-baseline pr-4">
                <h2 class="text-xl font-normal">Quiz Participations</h2>
                <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $quizzes_teams->total() }}</span>
            </div>
            <form action="" method="GET" class="inline-flex mr-4 flex-none">
                <input type="number" name="top_scorers" class="text-sm p-1 rounded border text-grey-darker w-16 mr-2" value="{{ Request::query('top_scorers') }}">
                <button class="btn is-green is-sm font-normal text-xs">Top Scorers</button>
            </form>
            <div class="flex-1 flex justify-end">
                <get-routes class="flex items-center space-x-1" select-classes="flex-1 control is-sm w-full max-w-64" route-name="admin.quizzes.teams.index" value="{{ $quiz ? $quiz->slug : '' }}">
                    <option value="" selected>All</option>
                    @foreach ($quizzes as $eachQuiz)
                    <option value="{{ $eachQuiz->slug}}">{{ ucwords($eachQuiz->event->title . ' - '. $eachQuiz->title) }}</option>
                    @endforeach
                    <template v-slot:button="{link}">
                        <a :href="link" class="btn is-blue is-sm">Filter</a>
                    </template>
                </get-routes>
            </div>
        </div>
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">Status</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Team</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Quiz</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Questions</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Responses</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Started</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Finished</th>
                <th class="text-xs uppercase font-light text-center px-4 py-2">Score</th>
                <th class="text-xs uppercase font-light text-center pr-6 py-2">Action</th>
            </quiz-team-row>
        </thead>
        <tbody>
            @foreach ($quizzes_teams as $quiz_team)
            <tr class="border-t hover:bg-grey-lighter">
                <td class="table-fit text-left pl-6 py-2 text-sm">
                    @if ($quiz_team->finished_at)
                        <span class="p-1 ml-1 rounded bg-red text-white font-extralight text-xs uppercase leading-none">Submitted</span>
                    @elseif ($quiz_team->started_at)
                        <span class="p-1 ml-1 rounded bg-green text-white font-extralight text-xs uppercase leading-none">Started</span>
                    @else
                        <span class="p-1 ml-1 rounded bg-grey font-extralight text-xs uppercase leading-none">Waiting</span>
                    @endif
                </td>
                <td class="table-fit text-left px-4 py-2">
                    <div class="text-sm mb-1">{{ $quiz_team->team->name }}</div>
                    <div class="flex -mx-1">
                        @foreach ($quiz_team->team->members as $member)
                            <span class="p-1 bg-blue text-white text-xs capitalize mx-1 rounded">{{ $member->first_name }}</span>
                        @endforeach
                    </div>
                </td>
                <td class="text-left px-4 py-2">
                    <a href="#" class="capitalize link">{{ $quiz_team->quiz->event->title . ' - ' . $quiz_team->quiz->title }}</a>
                    @if ($quiz_team->quiz->isActive)
                        <span class="p-1 ml-1 rounded bg-green text-white font-normal text-xs uppercase leading-none">LIVE</span>
                    @endif
                </td>
                <td class="table_fit text-center px-4 py-2">
                    <span class="px-2 py-1 rounded-full bg-grey text-xs">{{ $quiz_team->quiz->questions_count }}</span>
                </td>
                <td class="table_fit text-center px-4 py-2">
                    <span class="px-2 py-1 rounded-full bg-grey text-xs">{{ $quiz_team->responses_count }}</span>
                </td>
                <td class="table_fit text-left text-xs px-4 py-2">
                    @if ($quiz_team->started_at)
                        <span>{{ $quiz_team->started_at }}</span>
                    @endif
                </td>
                <td class="table_fit text-left text-xs px-4 py-2">
                    @if ($quiz_team->finished_at)
                        <span>{{ $quiz_team->finish_time }}</span>
                    @endif
                </td>
                <td class="table_fit text-center px-4 py-2">
                    @if ($quiz_team->score !== null)
                        <span class="p-1 bg-green text-white text-xs font-semibold rounded-full">{{ $quiz_team->score }}</span>
                    @else
                        <span class="p-1 bg-grey text-xs rounded">Not Evaluated</span>
                    @endif
                </td>
                <td class="table_fit text-center pl-4 pr-6 py-2">
                    <div class="inline-flex items-center justify-center">
                        <div class="flex items-center justify-center">
                            @if ($quiz_team->finished_at)
                                <form action="{{ route('admin.quizzes.teams.evaluate', $quiz_team->id) }}" method="POST" class="mr-2">
                                    @csrf
                                    <button type="submit" class="btn is-green is-sm whitespace-nowrap">Evaluate</button>
                                </form>
                            @else
                                <span class="p-1 bg-grey text-xs rounded mr-2">Not Completed</span>
                                <a href="{{ route('admin.quizzes.teams.extra-time', $quiz_team->id) }}" class="btn is-blue is-sm mr-2">
                                    Give Extra Time
                                </a>
                            @endif
                        </div>
                        <a href="{{ route('admin.quiz-participations.show', $quiz_team->id) }}" title="View Details">
                            @include('svg.view-show', ['classes' => 'text-grey hover:text-blue fill-current h-6'])
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        {{ $quizzes_teams->links() }}
    </div>
</div>
@endsection
