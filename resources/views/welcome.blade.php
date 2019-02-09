@extends('layouts.app')
@section('title', 'Home | ')
@section('content')
<div class="bg-blue flex flex-col justify-center flex-1 text-white">
    <div class="container mx-auto px-4">
        <h1>Intra Sankalan 2019 is here! </h1>
        <h3 class="mb-4">Pull up your socks</h3>
        <p>
            <a href="{{ route('login') }}" class="btn mr-1">Login</a>
            <a href="{{ route('register') }}" class="btn mr-1">Register</a>
        </p>
    </div>
</div>
@endsection
