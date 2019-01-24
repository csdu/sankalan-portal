@extends('layouts.app')
@section('title', 'Home | ')
@section('content')
<div class="bg-blue h-full text-white">
    <div class="container mx-auto flex flex-col justify-center h-full">
        <h1>Sankalan 2019 is here! </h1>
        <h3 class="mb-4">Pull up your socks</h3>
        <p class="w-1/2 mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iure, doloremque sit adipisci, placeat velit ea, ratione quae quod tenetur reiciendis incidunt ipsam quaerat. Inventore natus quod nihil suscipit, libero praesentium?</p>
        <p>
            <a href="{{ route('login') }}" class="btn mr-1">Login</a>
            <a href="{{ route('register') }}" class="btn mr-1">Register</a>
        </p>
    </div>
</div>
@endsection
