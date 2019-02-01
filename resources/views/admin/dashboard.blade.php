@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="header-title flex py-5">
    <h1 class="mx-10 flex-1 border-b pb-5">Dashboard</h1>
</div>
<div class="flex mb-4 border-b">
    <div class="w-1/5">
        <div class="nav-bar flex flex-col px-10">
            <div class="nav-bar-item border-grey h-30 pl-2">
                <h3 class="font-light py-5">Events</h3>
            </div>
            <div class="nav-bar-item border-grey h-30 pl-2">
                <h3 class="font-light py-5">Participations</h3>
            </div>
            <div class="nav-bar-item border-grey h-30 pl-2">
                <h3 class="font-light py-5">Logout</h3>
            </div>
        </div>
    </div>
    <div class="w-4/5 ">
        <div class="content-box flex items-center justify-center h-screen my-5 mr-10 bg-white">
            <h1>Welcome to your Dashboard.</h1>
        </div>
    </div>
</div>
    @endsection