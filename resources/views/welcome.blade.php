@extends('layouts.app')
@section('title', 'Home | ')
@section('content')
<div class="bg-blue flex flex-col justify-center flex-1 text-white">
    <div class="container mx-auto px-4">
        <h1>Sankalan 2019 is here! </h1>
        <h3 class="mb-6">Pull up your socks</h3>
        <p class="flex items-baseline">
            <a href="{{ route('register') }}" class="btn is-white shadow font-bold">Register</a>
            <span class="mx-2">or</span>
            <a href="{{ route('login') }}" class="text-white font-bold hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection
