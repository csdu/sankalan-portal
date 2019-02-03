@extends('layouts.admin')
@section('title', 'Manage Teams')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex">
            <h2 class="text-xl font-normal">Teams</h2>
            <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $teams->total() }}</span>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">ID</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Name</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Members</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Events</th>
                <th class="text-xs uppercase font-light text-right pr-6 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $team)
            <tr class="border-t hover:bg-grey-lighter">
                <td class="table-fit text-left pl-6 py-2 text-xs">{{ $team->uid }}</td>
                <td class="text-left capitalize px-4 py-2">
                    <a href="#" class="link">{{ $team->name }}</a>
                </td>
                <td class="text-left px-4 py-2">
                    @foreach($team->members as $member)
                    <span class="bg-blue text-white px-1 py-1 rounded text-xs">{{ $member->name }}</span>
                    @endforeach
                </td>
                <td class="table-fit text-center px-4 py-2">
                    <span class="px-2 py-1 bg-grey rounded-full text-xs">{{ $team->events_count }}</span>
                </td>
                <td class="table-fit text-right pr-6 py-2">
                    <button class="btn is-sm is-red">End</button>
                    <button class="btn is-sm is-green">Begin</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        {{ $teams->links() }}
    </div>
</div>
@endsection