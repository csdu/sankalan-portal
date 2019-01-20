@extends('layouts.master')
@section('title', 'Home | ')
@section('body')
<quiz-area :data-questions="{{ $questions->toJson() }}" :time-limit="45"></quiz-area>
@endsection
