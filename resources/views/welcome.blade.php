@extends('layouts.app')
@section('content')
<div class="flex flex-col justify-center flex-1 ">
    <div class="container mx-auto px-4 flex flex-col items-center lg:flex-row flex-1">
        <div class="my-6 lg flex flex-col justify-center items-start flex-1 text-center lg:text-left">
            <h1 class="mb-1 text-4xl">Sankalan <span class="text-2xl text-blue">{{ config('app.sankalan_start_time')->format('Y') }}</span></h1>
            <h3 class="mb-6">Compiling Innovations ...</h3>
            @if ($timeLeft <= 0)
                <p class="mb-6 text-xl sm:text-3xl">The event ended {{ config('app.sankalan_start_time')->diffForHumans() }}!!!</p>
            @else
                <countdown-timer :duration="{{ $timeLeft }}" v-slot="{timer, format}">
                    <div class="flex text-3xl sm:text-5xl -mx-4 mb-4">
                        <span v-if="timer.days > 0" class="inline-flex flex-col items-center justify-center p-3 md:p-6">
                            <span v-text="timer.days"></span>
                            <span class="mt-1 text-xs uppercase text-grey-dark tracking-wide font-semibold">Days</span>
                        </span>
                        <span class="inline-flex flex-col items-center justify-center p-3 md:p-6">
                            <span v-text="format(timer.hours, 2)"></span>
                            <span class="mt-1 text-xs uppercase text-grey-dark tracking-wide font-semibold">Hours</span>
                        </span>
                        <span class="inline-flex flex-col items-center justify-center p-3 md:p-6">
                            <span v-text="format(timer.minutes, 2)"></span>
                            <span class="mt-1 text-xs uppercase text-grey-dark tracking-wide font-semibold">Minutes</span>
                        </span>
                        <span class="inline-flex flex-col items-center justify-center p-3 md:p-6">
                            <span v-text="format(timer.seconds, 2)"></span>
                            <span class="mt-1 text-xs uppercase text-grey-dark tracking-wide font-semibold">Seconds</span>
                        </span>
                    </div>
                </countdown-timer>
            @endif
            <div class="inline-block text-left px-4 py-2 bg-blue-lightest text-blue-dark border border-blue rounded min-w-1/2">
                <h4 class="text-sm uppercase font-bold my-1">Note</h4>
                <p>
                    For gaming events and <b>hackDUCS</b>, registrations are being handled seperately, <br>
                    registration link will be updated on our
                    <a href="https://www.facebook.com/DUCS.Sankalan" class="font-bold underline">Facebook Page</a>.
                </p>
            </div>
        </div>
        <login-register v-slot="{ isRegister, register, login }">
            <div class="w-auto lg:w-1/3 flex flex-col justify-center items-center lg:items-end">
                <transition name="fade" mode="out-in">
                    @if ($timeLeft > 0)
                    <div v-if="isRegister" class="w-full card my-4">
                        <h2 class="card-header">
                            {{ __('Register') }}
                            <span class="text-xs text-grey-dark mx-1">or</span>
                            <a class="text-sm uppercase text-blue tracking-wide" href="#login" @click="login">Login</a>
                        </h2>
                        <form method="POST" action="{{ route('register') }}" class="card-content">
                            @csrf
                            <div class="flex mb-3">
                                <div class="flex-1 mr-2">
                                    <input type="text" placeholder="First Name" class="control{{ $errors->has('first_name') ? ' border-red' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>
                                    @if ($errors->has('first_name'))
                                    <span class="text-red mt-1" role="alert">
                                        {{ $errors->first('first_name') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="text" placeholder="Last Name" class="control{{ $errors->has('last_name') ? ' border-red' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>
                                    @if ($errors->has('last_name'))
                                    <span class="text-red mt-1" role="alert">
                                        {{ $errors->first('last_name') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="email" placeholder="Email" class="control{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('email') }}
                                </span> @endif
                            </div>
                            <div class="mb-3">
                                <input type="text" inputmode="tel" placeholder="Moblie Number (10-digit)" class="control{{ $errors->has('phone') ? ' border-red' : '' }}" name="phone" value="{{ old('phone') }}" required> @if ($errors->has('phone'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('phone') }}
                                </span> @endif
                            </div>
                            <div class="mb-4">
                                <input type="text" placeholder="College Name" class="control{{ $errors->has('college') ? ' border-red' : '' }}" name="college" required> @if ($errors->has('college'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('college') }}
                                </span> @endif
                            </div>
                            <div class="mb-4">
                                <input type="text" placeholder="Course (Year)" class="control{{ $errors->has('course') ? ' border-red' : '' }}" name="course" required> @if ($errors->has('course'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('course') }}
                                </span> @endif
                            </div>
                            <div class="mb-4">
                                <input type="password" placeholder="Password" class="control{{ $errors->has('password') ? ' border-red' : '' }}" name="password" required> @if ($errors->has('password'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('password') }}
                                </span> @endif
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
                    @endif
                    <div {{ $timeLeft > 0 ? 'v-else' : '' }} class="w-full card my-4">
                        <h2 class="card-header">
                            {{ __('Login') }}
                            @if ($timeLeft > 0)
                                <span class="text-xs text-grey-dark mx-1">or</span>
                                <a class="text-sm uppercase text-blue tracking-wide" href="#register" @click="register">Register</a>
                            @endif
                        </h2>

                        <form method="POST" action="{{ route('login') }}" class="card-content">
                            @csrf
                            <div class="mb-3">
                                <input type="email" placeholder="Email" class="control{{ $errors->has('email') ? ' border-red' : '' }}" name="email" value="{{ old('email') }}" required autofocus> 
                                @if ($errors->has('email'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('email') }}
                                </span> @endif
                            </div>

                            <div class="mb-4">
                                <input type="password" placeholder="Password" class="control{{ $errors->has('password') ? ' border-red' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                <span class="text-red mt-1" role="alert">
                                    {{ $errors->first('password') }}
                                </span>
                                @endif
                            </div>

                            <div class="whitespace-nowrap mb-6 flex items-center">
                                <input type="checkbox" name="remember" id="remember" {{ old( 'remember') ? 'checked' : '' }}>
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
                </transition>
            </div>
        </login-register>
    </div>
</div>
@endsection
