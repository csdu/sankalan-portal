@extends('layouts.admin')
@section('title', 'Manage Users')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex items-center">
            <h2 class="text-xl font-normal">Users</h2>
            <span class="ml-2 bg-blue-500 text-white rounded-full px-2 py-1 text-xs">{{ $users->total() }}</span>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-slate-200">
                <th class="text-xs uppercase text-left pl-6 py-2">ID</th>
                <th class="text-xs uppercase text-left px-4 py-2">Name</th>
                <th class="text-xs uppercase text-left px-4 py-2">Email</th>
                <th class="text-xs uppercase text-left px-4 py-2">Phone</th>
                <th class="text-xs uppercase text-left px-4 py-2">College</th>
                <th class="text-xs uppercase text-left pr-6 py-2">Course</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-t border-slate-200 hover:bg-slate-100">
                <td class="text-left px-4 pl-6 py-2 text-xs">{{ $user->uid }}</td>
                <td class="text-left capitalize px-4 py-2">
                    <a href="#" class="link">{{ $user->name }}</a>
                </td>
                <td class="text-left px-4 py-2">
                    {{ $user->email }}
                </td>
                <td class="text-left px-4 py-2">
                    {{ $user->phone }}
                </td>
                <td class="text-left px-4 py-2">
                    {{ $user->college }}
                </td>
                <td class="text-left pr-6 py-2">
                    {{ $user->course }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        @if($users->hasPages())
        {{ $users->links() }}
        @else
        <p class="text-slate-600 text-center">That's all folks</p>
        @endif
    </div>

</div>
@endsection
