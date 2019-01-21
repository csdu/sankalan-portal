@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 flex h-full justify-center items-center">
    <div class="w-full sm:w-3/4 lg:w-1/2 p-6 bg-white text-black border rounded shadow">
        <h2 class="mb-6">{{ __('Reset Password') }}</h2>

        @if (session('status'))
            <div class="bg-green-lighter px-4 py-2 my-6 border-green-dark text-green-dark" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <input type="email" class="w-full p-2 bg-white text-grey-dark border hover:border-blue focus:border-blue{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email">
                @if ($errors->has('email'))
                    <span class="inline-block  text-red w-full mt-2" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="p-2 uppercase text-xs tracking-wide bg-blue hover:bg-blue-dark text-white">
                {{ __('Send Password Reset Link') }}
            </button>
        </form>
    </div>
</div>
@endsection
