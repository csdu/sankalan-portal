@extends('layouts.master')
@push('stylesheets')
<style>
    body {
        background-color: var(--color-grey-lighter);
        background-image: url("{{ asset('/svg/circuit-bg.svg') }}");
    }
</style>
<link rel="stylesheet"
href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.17.1/build/styles/default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.5.1/katex.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/github-markdown-css/2.2.1/github-markdown.css"/>
@endpush
@section('body')
    <div class="flex flex-col min-h-screen px-12">
        @include('flash::message')
        <div class="my-4">
            <h2 class="text-center capitalize mb-2">{{ $quiz->event->title }}</h2>
            <h5 class="text-xs text-blue-600 uppercase text-center">{{ $quiz->title }}</h5>
        </div>
        <quiz-area
            redirect-to="{{ route('dashboard') }}"
            action="{{ route('quizzes.response.store', $quiz) }}"
            save-action="{{ route('quizzes.response.save', $quiz) }}"
            :data-questions="{{ $quiz->questions }}"
            :data-questions-attachments="{{ $questionsAttachments }}"
            :time-limit="{{ $participation->timeLeft }}"
            :data-responses="{{ $quiz_response->responses ?? [] }}">
        </quiz-area>
    </div>
@endsection
