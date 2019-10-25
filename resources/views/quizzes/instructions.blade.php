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
                @if(count($quiz->instructions))
                <ol class="text-base leading-normal">
                    @foreach ($quiz->instructions as $instruction)
                        <li class="py-1">
                            {!! $instruction !!}
                        </li>
                    @endforeach
                </ol>
                @else
                    <p class="py-2 text-center text-grey-dark">No Instructions provided</p>
                @endif
            </div>
        </div>

        <div class="card seperated my-6">
            <div class="card-header">
                <h3>Quiz Interface</h3>
            </div>
            <div class="card-content">
                <h4 class="mb-2">General Instructions</h4>
                <ul class="text-base leading-normal mb-4">
                    <li class="py-1">Your responses are saved when you navigate to other questions, do not click <button class="btn is-green is-sm">submit</button> or press <kbd class="p-1 bg-black text-white rounded text-xs">&crarr; Enter</kbd>, unless you want to finally submit.</li>
                </ul>
                
                <h4 class="mb-2">Mouse Controls</h4>
                <ul class="text-base leading-normal mb-4">
                    <li class="py-1">Use mouse cursor to highlight option.</li>
                    <li class="py-1">
                        Left Click with mouse to select or unselect the option. 
                        Once you select the option your response is automatically saved,
                        you can proceed to next question.
                        Make sure you <span class="text-red">do not reload the browser</span>,
                        you will lose all of your progress, no extra time will be provided in that case.
                    </li>
                    <li class="py-1">
                        In case of questions requiring input from user, use <button class="btn is-blue is-sm">edit</button> button to edit your response,
                        and then use <button class="btn is-sm">save</button> button to save your entered response.
                    </li>
                    <li class="py-1">Use <button class="btn is-green is-sm">previous</button> and <button class="btn is-green is-sm">next</button> to goto previous and next question respectively.</li>
                    <li class="py-1">Use <button class="btn is-green is-sm">Submit</button> to submit your response.</li>
                </ul>
                <h4 class="mb-2">Keyboard Controls</h4>
                <ul class="text-base leading-normal">
                    <li class="py-1">Use <kbd class="p-1 text-xs bg-black text-white rounded">&uarr; Up</kbd> and <kbd class="p-1 text-xs bg-black text-white rounded">&darr; Down</kbd> keys to highlight option.</li>
                    <li class="py-1">
                        Use <kbd class="p-1 text-xs bg-black text-white rounded">Space</kbd> to select or unselect the option. 
                        Once you select the option your response is automatically saved,
                        you can proceed to next question.
                        Make sure you <span class="text-red">do not reload the browser</span>,
                        you will lose all of your progress, no extra time will be provided in that case.
                    </li>
                    <li class="py-1">
                        In case of questions requiring input from user, use <button class="btn is-blue is-sm">edit</button> button to edit your response,
                        and then use <button class="btn is-sm">save</button> button to save your entered response.
                    </li>
                    <li class="py-1">Use <kbd class="p-1 text-xs bg-black text-white rounded">&larr; Left</kbd> and <kbd class="p-1 text-xs bg-black text-white rounded">&rarr; Right</kbd> to goto previous and next question respectively.</li>
                    <li class="py-1">Use <kbd class="p-1 text-xs bg-black text-white rounded">&crarr; Enter</kbd> to submit your response.</li>
                </ul>
            </div>
        </div>

        <form class="" action="{{ route('quizzes.take', $quiz) }}" method="GET">
            @csrf
            <button class="btn is-green">
                @if ($quiz->started_at)
                    Continue Quiz
                @else
                    Go For Quiz                    
                @endif
            </button>
        </form>
    </div>
@endsection