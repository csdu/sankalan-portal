@extends('layouts.app')

@section('content')
    
    <div class="container mx-auto">
        <h2 class="text-center mt-4 mb-2">{{ $quiz->event->title}}</h2>
        <h3 class="text-center mb-4">{{ $quiz->title }}</h3>

        <div class="card seperated my-6 lg:w-1/2 mx-auto">
            <div class="card-header">
                <h3>Verify</h3>
            </div>
            <div class="card-content">
                <form action="{{ route('quizzes.verify', $quiz) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <input type="text" placeholder="Enter Token..." class="control{{ $errors->has('quiz_verify_token') ? ' border-red' : '' }}" name="quiz_verify_token" value="{{ old('quiz_verify_token') }}" required autofocus>
                        @if ($errors->has('quiz_verify_token'))
                            <span class="text-red bg-red-lighter px-2 py-1 border-red" role="alert">
                                <strong>{{ $errors->first('quiz_verify_token') }}</strong>
                            </span>
                        @endif
                    </div>
                    <button type="submit" class="btn is-blue">Submit</button>
                </form>
            </div>
        </div>
    </div>
    
@endsection