@extends('layouts.app')

@section('content')
<div class="container mx-auto flex justify-center h-full items-center px-4">
    <div class="w-full sm:w-3/4 lg:w-1/3">
        <div class="card">
            <h2 class="card-header">{{ __('Register') }}</h2>
            <form method="POST" action="{{ route('register') }}" class="card-content">
                @csrf
                <div class="mb-3">
                    <input type="name" placeholder="Full Name" class="control{{ $errors->has('name') ? ' border-red' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <input type="email" placeholder="Email" class="control{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-4">
                    <input type="text" placeholder="College Name" class="control{{ $errors->has('college') ? ' border-red' : '' }}" name="college" required>

                    @if ($errors->has('college'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('college') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-4">
                    <input type="text" placeholder="Course (Year)" class="control{{ $errors->has('course') ? ' border-red' : '' }}" name="course" required>

                    @if ($errors->has('course'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('course') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-4">
                    <input type="password" placeholder="Password" class="control{{ $errors->has('password') ? ' border-red' : '' }}" name="password" required>

                    @if ($errors->has('password'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-4">
                    <input type="password" placeholder="Confirm Password" class="control{{ $errors->has('password') ? ' border-red' : '' }}" name="password_confirmation" required>
                </div>
                <div>
                    <button type="submit" class="btn is-blue">
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
