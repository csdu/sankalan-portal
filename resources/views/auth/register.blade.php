@extends('layouts.app')

@section('content')
<div class="container mx-auto flex-1 flex justify-center items-center px-4">
    <div class="w-full sm:w-3/4 lg:w-1/3">
        <div class="card my-4">
            <h2 class="card-header">{{ __('Register') }}</h2>
            <form method="POST" action="{{ route('register') }}" class="card-content">
                @csrf
                <div class="flex mb-3">
                    <div class="flex-1 mr-2">
                        <input type="text" placeholder="First Name" class="control{{ $errors->has('first_name') ? ' border-red' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>
                        @if ($errors->has('first_name'))
                            <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="text" placeholder="Last Name" class="control{{ $errors->has('last_name') ? ' border-red' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>
                        @if ($errors->has('last_name'))
                            <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <input type="email" placeholder="Email" class="control{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-3">
                    <input type="phone" placeholder="Moblie Number (10-digit)" class="control{{ $errors->has('phone') ? ' border-red' : '' }}" name="phone" value="{{ old('phone') }}" required>
                    @if ($errors->has('phone'))
                        <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
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
@endsection
