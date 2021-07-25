@extends('layouts.app')

@section('content')
<div class="container mx-auto flex-1 flex justify-center items-center px-4">
    <div class="w-full md:w-3/4 lg:w-1/3">
        <div class="card">
            <h2 class="card-header">
                {{ __('Login') }}
                <span class="text-xs text-grey-dark mx-1">or</span>
                <a class="text-sm uppercase text-blue" href="{{ route('register') }}">Register</a>
            </h2>

            <form method="POST" action="{{ route('login') }}" class="card-content">
                @csrf
                <div class="mb-3">
                    <input type="email" placeholder="Email" class="control{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                    <span class="text-red mt-1" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="mb-4">
                    <input type="password" placeholder="Password" class="control{{ $errors->has('password') ? ' border-red' : '' }}" name="password" required>

                    @if ($errors->has('password'))
                    <span class="text-red mt-1" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="whitespace-nowrap mb-3 flex items-center">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="ml-1" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div class="flex justify-between items-baseline">
                    <button type="submit" class="btn is-blue">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
