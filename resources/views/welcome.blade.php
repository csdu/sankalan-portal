@extends('layouts.app')
@section('title', 'Home | ')
@section('content')
<div class="container mx-auto h-full">
    <quiz-area :data-questions="{{ $questions->toJson() }}" :time-limit="45"></quiz-area>
</div>
@endsection
