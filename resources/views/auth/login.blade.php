@extends('layouts.app')

@section('content')
<div class="container mx-auto flex justify-center h-full items-center px-4">
    <div class="w-full sm:w-3/4 lg:w-1/3">
        <div class="p-6 bg-white shadow rounded-lg border">
            <h1 class="mb-6">{{ __('Login') }}</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <input type="email" placeholder="Email" class="w-full p-2 bg-white text-grey-darker border hover:border-blue focus:border-blue{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="mb-4">
                    <input type="password" placeholder="Password" class="w-full p-2 bg-white text-grey-darker border hover:border-blue focus:border-blue{{ $errors->has('password') ? ' border-red' : '' }}" name="password" required>

                    @if ($errors->has('password'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="ml-1" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a class="hover:underline ml-auto" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="px-3 py-2 uppercase text-xs tracking-wide bg-blue text-white hover:bg-blue-dark">
                        {{ __('Login') }}
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
