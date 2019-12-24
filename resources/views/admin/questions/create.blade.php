@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">New Question for <small class="text-blue">{{ $quiz->title }}</small></h2>
            </div>
            <div>
                <span class="text-sm font-normal">
                    Total Questions
                </span>
                <span class="px-2 py-1 rounded-full bg-blue text-white text-xs">{{ $quiz->questions()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
            @csrf

            <div class="flex -mx-2">
                <div class="w-1/3 px-2">
                    <label class="control">
                        Q.No.
                    </label>
                    <input class="control w-100" name="qno" type="number" value="{{ old('qno') }}" min="1" required>
                </div>
    
                <div class="w-1/3 px-2">
                    <label class="control">
                        Positive score
                    </label>
                    <input class="control" name="positive_score" min="1" type="number" value="{{ old('positive_score', 4) }}" required>
                </div>
    
                <div class="w-1/3 px-2">
                    <label class="control">
                        Negative score
                    </label>
                    <input class="control" name="negative_score" min="0" type="number" value="{{ old('positive_score', 1) }}" required>
                </div>
            </div>

            <label class="control">Question</label>
		    <markdown-editor></markdown-editor>

            <question-type></question-type>

            <button class="btn is-blue mt-4" type="submit">Create</button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn">Cancel</a>
        </form>
    </div>
</div>
@endsection