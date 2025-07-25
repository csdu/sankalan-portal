@extends('layouts.admin')
@section('title', 'Manage Teams')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex items-center">
            <h2 class="text-xl font-normal">Teams</h2>
            <span class="ml-2 bg-blue-500 text-white rounded-full px-2 py-1 text-xs">{{ $teams->total() }}</span>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-200">
                <th class="text-xs uppercase text-left pl-6 py-2">ID</th>
                <th class="text-xs uppercase text-left px-4 py-2">Name</th>
                <th class="text-xs uppercase text-left px-4 py-2">Members</th>
                <th class="text-xs uppercase text-left px-4 py-2">Events</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $team)
            <tr class="border-t border-slate-200 hover:bg-slate-100">
                <td class="text-left pl-6 py-2 text-sm">{{ $team->uid }}</td>
                <td class="text-left capitalize px-4 py-2">
                    <a href="#" class="link">{{ $team->name }}</a>
                </td>
                <td class="text-left px-4 py-2">
                    @foreach($team->members as $member)
                    <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs">{{ $member->name }}</span>
                    @endforeach
                </td>
                <td class="text-center px-4 py-2">
                    <span class="px-2 py-1 bg-slate-200 rounded-full text-xs">{{ $team->events_count }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        @if($teams->hasPages())
        {{ $teams->links() }}
        @else
        <p class="text-slate-600 text-center">That's all folks</p>
        @endif
    </div>
</div>
@endsection
