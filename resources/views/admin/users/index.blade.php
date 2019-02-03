@extends('layouts.admin')
@section('title', 'Manage Users')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex">
            <h2 class="text-xl font-normal">Users</h2>
            <span class="ml-2 bg-blue text-white rounded-full p-1 text-xs">{{ $users->total() }}</span>
        </div>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">ID</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Name</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Gender</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Email</th>
                <th class="text-xs uppercase font-light text-left px-4 py-2">Phone</th>
                <th class="text-xs uppercase font-light text-left pr-6 py-2">Course</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-t hover:bg-grey-lighter">
                <td class="table-fit text-left px-4 pl-6 py-2 text-xs">{{ $user->uid }}</td>
                <td class="text-left capitalize px-4 py-2">
                    <a href="#" class="link">{{ $user->name }}</a>
                </td>
                <td class="table-fit text-right px-4 py-2">
                    {{ $user->gender }}
                </td>
                <td class="text-left px-4 py-2">
                    {{ $user->email }}
                </td>
                <td class="table-fit text-center px-4 py-2">
                    {{ $user->phone }}
                </td>
                <td class="table-fit text-left pr-6 py-2">
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
        <p class="text-grey-dark text-center">That's all folks</p>
        @endif
    </div>
    
</div>
@endsection