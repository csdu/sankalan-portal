@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">Question for <small class="text-blue-500">{{ $quiz->title }}</small></h2>
                <div>
                    @if ($quiz->isActive)
                    <span class="p-1 ml-1 rounded bg-emerald-500 text-white font-extralight text-xs uppercase leading-none">LIVE</span>
                    @elseif($quiz->isClosed)
                    <span class="p-1 ml-1 rounded bg-red-500 text-white font-extralight text-xs uppercase leading-none">Closed</span>
                    @else
                    <span class="p-1 ml-1 rounded bg-slate-400 font-extralight text-xs uppercase leading-none">Offline</span>
                    @endif
                </div>
            </div>

            <div>
                <span class="text-sm font-normal">
                    Total Questions
                </span>
                <span class="px-2 py-1 rounded-full bg-blue-500 text-white text-xs">{{ $quiz->questions()->count() }}</span>
                <span class="px-2"></span>
                @unless ($quiz->isactive || $quiz->isClosed)
                <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="btn is-sm is-blue">Add new question</a>
                @endunless
            </div>
        </div>
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-slate-100">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">Qno.</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Question</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Positive Score</th>
                <th class="text-xs uppercase text-left px-4 py-2">Negative Score</th>
                <th class="text-xs uppercase text-center pr-6 py-2">Action(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quiz->questions as $question)
            <tr class="border-t border-slate-200 hover:bg-slate-50">
                <td class="text-center pl-6 py-2">
                    {{ $question->qno }}
                </td>
                <td class="text-left px-4 py-2">
                    <a href="{{ route('admin.quizzes.questions.show', [$quiz, $question]) }}" class="link">
                        {{ str_limit(strip_tags($question->text), 100, '...') }}
                    </a>
                </td>
                <td class="text-center px-4 py-2">
                    {{ $question->positive_score }}
                </td>
                <td class="text-center px-4 py-2">
                    {{ $question->negative_score }}
                </td>
                <td class="text-center pr-6 py-2">
                    <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="btn is-sm is-blue">Edit</a>
                    @unless ($quiz->isactive || $quiz->isClosed)
                    <form onsubmit="return confirm('Are you about deleting it.')" class="inline-block" action="{{ route('admin.quizzes.questions.delete', [$quiz, $question]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn is-sm is-red">Delete</button>
                    </form>
                    @endunless
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <p class="text-center text-slate-600">That's all folks!</p>
    </div>
</div>
@endsection
