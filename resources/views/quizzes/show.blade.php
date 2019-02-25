@extends('layouts.master')
@push('stylesheets')
<style>
    body {
        background-color: #f1f5f8;
        background-image: url("{{ asset('/svg/circuit-bg.svg') }}");
    }
</style>
@endpush
@section('body')
    <div class="flex flex-col min-h-screen px-12">
        <div class="my-4">
            <h2 class="text-center capitalize mb-2">{{ $quiz->event->title }}</h2>
            <h5 class="text-xs text-blue uppercase text-center">{{ $quiz->title }}</h5>
        </div>
        <quiz-area 
            redirect-to="{{ route('dashboard') }}"
            action="{{ route('quizzes.response.store', $quiz) }}"
            :data-questions="{{ $quiz->questions }}" 
            :time-limit="{{ $quiz->participations->first()->timeLeft }}">
        </quiz-area>
    </div>    
@endsection