@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 flex-1">
    <div class="card my-8">
        <h1 class="card-header">Dashboard</h1>

        <div class="card-content">
            @if (session('status'))
                <div class="bg-green-lighter text-green border border-green" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            You are logged in!
        </div>
    </div>
</div>
@endsection
