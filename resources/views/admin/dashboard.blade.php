@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated mb-8">
    <div class="card-header">
        <h2>Dashboard</h2>
    </div>
    <div class="card-content h-32">

    </div>
</div>
<div class="flex flex-wrap -mx-2">
    @foreach(range(1,10) as $i)
    <div class="w-1/2 px-2 my-2">
        <div class="card seperated">
            <div class="card-header">
                <h2 class="text-xl">Cards</h2>
            </div>
            <div class="card-content h-16">

            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection