@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="card h-full">
        <div class="card-header">
            <h3>Event Participations</h3>
        </div>
        <div class="card-content">
            <div class="flex justify-end mb-2">
                <form action="#" method="GET" class="flex">
                    <select class="control is-sm" name="event">
                        <option value="" selected>All Events</option>
                        @foreach ($participations as $participation)
                            <option value="{{ $participation->event->id}}">{{ $participation->event->title }}</option>
                        @endforeach
                    </select>
                    <button class="btn is-blue ml-2 is-sm" type="submit">Filter</button>
                </form>
            </div>
            <table class="w-full">
                <tr class="flex justify-between py-3 border-b-2">
                    <th class="flex-1">Team ID</th>
                    <th class="flex-1">Team Name</th>
                    <th class="flex-1">Participated in</th>
                </tr>
                @foreach ($participations as $participation)
                    <tr class="flex py-3 border-b">
                    <td class="flex-1 text-center">{{ $participation->team->uid }}</td>
                    <td class="flex-1 text-center">{{ $participation->team->name }}</td>
                    <td class="flex-1 text-center">{{ $participation->event->title }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection