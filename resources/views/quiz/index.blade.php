@extends('layouts.app')
@section('content')
    <div class="container mx-auto h-full">
        <quiz-area 
            :data-questions="{{ $quiz->questions }}" 
            :time-limit="{{ $quiz->timeLimit }}">
            <div class="mt-4 mb-12" slot="header">
                <h2 class="text-center capitalize mb-2">{{ $quiz->event->title }}</h2>
                <h4 class="text-center">{{ $quiz->title }}</h4>
            </div>
        </quiz-area>
    </div>    
@endsection