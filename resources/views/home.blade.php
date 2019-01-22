@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="card mb-16">
        <h1 class="mb-4">Dashboard</h1>

        <div class="card-body">
            @if (session('status'))
                <div class="bg-green-lighter text-green border border-green" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
    </div>

    <h2 class="mb-6">Events</h2>
    @foreach($events as $event)
    <div class="card mb-4">
        <h1 class="mb-4 capitalize">{{ $event->title }} 
            @if($event->hasQuiz)
                <span class="ml-2 p-1 text-xs uppercase bg-blue text-white">Online Quiz</span> 
            @endif
        </h1>

        <p>{{ $event->description }}</p>
    </div>
    @endforeach
</div>
@endsection
