@extends('layouts.app')

@section('content')
<div class="container mx-auto flex justify-center h-full items-center px-4">
    <div class="w-full sm:w-3/4 lg:w-1/3">
        <div class="p-6 bg-white shadow rounded-lg border">
            <h1 class="mb-6">{{ __('Register') }}</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <input type="name" placeholder="Full Name" class="w-full p-2 bg-white text-grey-darker border hover:border-blue focus:border-blue{{ $errors->has('name') ? ' border-red' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <input type="email" placeholder="Email" class="w-full p-2 bg-white text-grey-darker border hover:border-blue focus:border-blue{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required>
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
                <div class="mb-4">
                    <input type="password" placeholder="Confirm Password" class="w-full p-2 bg-white text-grey-darker border hover:border-blue focus:border-blue{{ $errors->has('password') ? ' border-red' : '' }}" name="password_confirmation" required>
                </div>
                <div class="flex justify-between">
                    <button type="submit" class="px-3 py-2 uppercase text-xs tracking-wide bg-blue text-white hover:bg-blue-dark">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

        <div class="col-md-6">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </div>
@endsection
