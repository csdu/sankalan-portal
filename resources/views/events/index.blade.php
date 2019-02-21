@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 flex-1">
    <h2 class="my-12 text-3xl">Events</h2>
    <div class="flex flex-wrap -mx-2">
        @foreach($events as $event)
            @include('partials.event-card', ['event' => $event])
        @endforeach
    </div>
</div>
@endsection
