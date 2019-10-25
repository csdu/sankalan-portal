@extends('layouts.admin')
@section('title', 'Quiz Participation Details | ')
@section('content')
<div class="card seperated mb-8">
    <div class="card-header">
        <h2 class="text-xl font-normal">Quiz Participation</h2>
    </div>
    <div>
        <dl class="flex py-2 bg-blue-lightest px-4">
            <dt class="w-64 font-bold">Team Members</dt>
            <dd class="flex-1 -mx-1">
                @foreach($QuizResponse->team->members as $member)
                    <span class="mx-1 p-1 bg-blue text-white rounded text-xs font-bold">{{ title_case($member->name) }}</span>
                @endforeach
            </dd>
        </dl>
        <dl class="flex py-2 bg-blue-lightest px-4">
            <dt class="w-64 font-bold">Event</dt>
            <dd class="flex-1">
                {{ $QuizResponse->quiz->event->title }}
            </dd>
        </dl>
        <dl class="flex py-2 bg-blue-lightest px-4">
            <dt class="w-64 font-bold">Quiz</dt>
            <dd class="flex-1">
                {{ $QuizResponse->quiz->title }}
            </dd>
        </dl>
        <dl class="flex py-2 bg-blue-lightest px-4">
            <dt class="w-64 font-bold">Responses</dt>
            <dd class="flex-1">
                {{ $QuizResponse->responses->count() }}
            </dd>
        </dl>
        <dl class="flex py-2 bg-blue-lightest px-4">
            <dt class="w-64 font-bold">Total Score</dt>
            <dd class="flex-1">
                <b>{{ $QuizResponse->score }}</b> out of {{ $QuizResponse->quiz->questions->sum('positive_score') }}
            </dd>
        </dl>
    </div>
</div>
<section class="mb-8">
    <h2 class="mb-6">Responses</h2>
    @foreach($QuizResponse->responses as $response)
        <div class="card seperated mb-8">
            <h3 class="card-header">
                Question #{{ $response->question->qno }}
            </h3>
            <div class="card-content bg-blue-lightest flex items-start">
                <div class="question mr-4 flex-1">
                    <div class="card px-3 pt-3 pb-6 relative overflow-hidden">
                        <strong class="float-left mr-2">Q.{{ $response->question->qno }}.</strong>
                        <p>{!! $response->question->text !!}</p>
                    </div>
                    @if($response->question->code)
                    <pre 
                    class="card p-4 font-mono my-4 leading-normal tracking-wide whitespace-pre-wrap max-h-64 overflow-y-auto"
                    >{{ str_replace('<br>', "\n", $response->question->code) }}</pre>
                    @endif
                    @if($response->question->illustration)
                    <div class="flex justify-center my-4">
                        <img src="{{ $response->question->illustration}}" class="max-w-full rounded shadow-lg">
                    </div>
                    @endif
                    @if(count($response->question->choices))
                        <ul class="choices-list list-reset mt-8 flex flex-wrap -mx-2">
                            @foreach($response->question->choices as $choice)
                            <li class="mb-3 w-full md:w-1/2 px-2">
                                <label class="relative card flex items-center py-2 pr-3 pl-8 {{ $choice->isCorrect() && $response->isChosen($choice) ? 'bg-green text-white' : ($response->isChosen($choice) ? 'bg-red text-white' : '') }}">
                                    @if($choice->isCorrect())
                                        <div class="absolute left-0 inset-y-0 flex items-center ml-2">
                                            @include('svg.checkmark', ['classes' => ($response->isChosen($choice) ? '': 'text-green ') . "fill-current h-4"])
                                        </div>
                                    @elseif($response->isChosen($choice) && !$choice->isCorrect())
                                        <div class="absolute left-0 inset-y-0 flex items-center ml-2">
                                            @include('svg.close', ['classes' => "fill-current h-4"])
                                        </div>
                                    @endif
                                    
                                    @if($choice->illustration)
                                        <img src="{{ $choice->illustration }}" alt="{{ $choice->text }}" class="rounded my-2 max-w-full">
                                    @endif
                                    @if($choice->code)
                                        <pre class="my-2">
                                            {{ str_replace('<br>', "\n", $choice->code) }}
                                        </pre>
                                    @endif
                                    <p class="ml-1">{!! $choice->text !!}</p>
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="list-reset mt-8 flex flex-wrap -mx-2">
                            <li class="w-full mb-3 px-2">
                                <p class="{{ $response->question->correct_answer_keys->contains($response->response_keys) ? 'text-green' : 'text-red' }}">
                                    {{ $response->response_keys }}
                                </p>
                            </li>
                            @foreach($response->question->correct_answer_keys as $answer_key)
                            <li class="mb-3 w-1/2 px-2">
                                <div class="card py-2 pr-3 pl-8 flex border-0 {{ $answer_key == $response->response_keys ? 'bg-green text-white' : '' }}">
                                    <div class="absolute left-0 inset-y-0 flex items-center ml-2">
                                        @include('svg.checkmark', ['classes' => "fill-current h-4"])
                                    </div>
                                    <p>{{ $answer_key }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="card flex text-white border-0 {{ $response->score <= 0 ? 'bg-red' : 'bg-green' }}">
                    <div class="card-content flex flex-col items-center justify-center">
                        <span class="text-5xl">{{ $response->score }}</span>
                        <span class="uppercase text-sm">score</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</section>
@endsection