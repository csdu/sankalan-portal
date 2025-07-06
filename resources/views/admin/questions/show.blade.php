@extends('layouts.admin')
@section('title', 'Quizzes')
@push('stylesheets')
<link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.17.1/build/styles/default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.5.1/katex.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/github-markdown-css/2.2.1/github-markdown.css"/>
@endpush
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">
                    Question <small class="text-blue-500">{{ $question->qno }}</small>
                    of <small class="text-blue-500">{{ $quizTitle }}</small>
                </h2>
            </div>
            <div>
                <span class="text-sm font-normal">
                    Total Options
                </span>
                <span class="px-2 py-1 rounded-full bg-blue-500 text-white text-xs">{{ $question->choices->count() }}</span>
            </div>
        </div>
    </div>
    <div class="px-6 py-4">
        <markdown-preview class="mb-2" markdown="{{ $question->text }}" />

        @foreach ($questionAttachments as $questionAttachment)
            <img class="max-w-full" src="/question_attachments/{{ $questionAttachment->id }}">
        @endforeach

    </div>
    @if($question->choices->count() > 0)
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-200">
                <th class="text-xs uppercase text-left pl-6 py-2">Option</th>
                <th class="text-xs uppercase text-left px-4 py-2">Text</th>
                <th class="text-xs uppercase text-center pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($question->choices as $choice)
                <tr class="border-t border-slate-200 hover:bg-slate-100 {{ $choice->isCorrect() ? 'bg-emerald-500 text-white hover:bg-emerald-600' : '' }}">
                    <td class="text-left pl-6 py-2">
                        {{ $choice->key }}
                    </td>
                    <td class="text-left px-4 py-2">
                        {{ $choice->text }}
                    </td>
                    <td class="text-center pr-6 py-2">
                        <!-- Actions can be added here if needed -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="px-6 flex flex-wrap py-2">
        <span class="mr-4">Correct Phrases:</span>
        @foreach($question->correct_answer_keys as $key)
            <span class="px-2 mx-1 rounded bg-emerald-600 text-white font-bold">{{ $key }}</span>
        @endforeach
    </p>
    @endif
    <div class="card-footer">
        <p class="text-center text-slate-600">That's all folks!</p>
    </div>
</div>
@endsection
