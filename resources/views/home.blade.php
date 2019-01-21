@extends('layouts.app')

@section('content')
<div class="container mx-auto flex h-full justify-center items-center">
    <div class="md:w-2/3 lg:w-1/2">
        <div class="bg-white p-6 border rounded shadow text-black">
            <h1 class="mb-4">Dashboard</h1>

            <div class="card-body">
                @if (session('status'))
                    <div class="bg-green-lighter text-green border border-green" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!
            </div>
        </div>
    </div>
</div>
@endsection
