@extends('layouts.master')
@section('body')
    <div class="flex flex-col min-h-screen px-12">
        <div class="my-4">
            <h2 class="text-center capitalize mb-2">{{ $quiz->event->title }}</h2>
            <h4 class="text-center">{{ $quiz->title }}</h4>
        </div>
        <quiz-area 
            redirect-to="{{ route('dashboard') }}"
            action="{{ route('quizzes.response.store', $quiz) }}"
            :data-questions="{{ $quiz->questions }}" 
            :time-limit="{{ $quiz->participations->first()->timeLeft }}">
        </quiz-area>
    </div>    
@endsection