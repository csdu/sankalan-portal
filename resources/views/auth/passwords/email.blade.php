@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 flex-1 flex justify-center items-center">
    <div class="w-full sm:w-3/4 lg:w-1/2">
        <div class="card">
            <h2 class="card-header">{{ __('Reset Password') }}</h2>

            <div class="card-content">
                @if (session('status'))
                    <div class="bg-emerald-100 px-4 py-2 my-6 border-emerald-700 text-emerald-700" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="email" class="control{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required placeholder="Email" autofocus>
                        @if ($errors->has('email'))
                            <span class="inline-block  text-red-500 w-full mt-2" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <button type="submit" class="btn is-blue">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
