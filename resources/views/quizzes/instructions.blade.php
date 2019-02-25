@extends('layouts.app')
@section('title', "{$quiz->event->title} - {$quiz->title} | ")
@section('content')
    <div class="container mx-auto">
        <h2 class="text-center mt-4 mb-2">{{ $quiz->event->title}}</h2>
        <h3 class="text-center mb-4">{{ $quiz->title }}</h3>
        <div class="card seperated my-6">
            <div class="card-header">
                <h3>Instructions</h3>
            </div>
            <div class="card-content">
                @empty($quiz->participations)
                <ol class="text-base leading-normal">
                    @foreach ($quiz->instructions as $instruction)
                        <li class="py-1">
                            {!! $instruction !!}
                        </li>
                    @endforeach
                </ol>
                @else
                    <p class="py-2 text-center text-grey-dark">No Instructions provided</p>
                @endempty
            </div>
        </div>

        <form class="" action="{{ route('quizzes.take', $quiz) }}" method="POST">
            @csrf
            <button class="btn is-green">Go For Quiz</button>
        </form>
    </div>
@endsection