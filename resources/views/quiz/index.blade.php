@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-4 h-full">
        <quiz-area :data-questions="{{ $quiz->questions }}" :time-limit="{{ $quiz->timeLimit }}"></quiz-area>
    </div>    
@endsection