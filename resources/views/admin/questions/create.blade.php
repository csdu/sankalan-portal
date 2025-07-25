@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@push('stylesheets')
<link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@9.17.1/build/styles/default.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.5.1/katex.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/github-markdown-css/2.2.1/github-markdown.css"/>
@endpush
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">New Question for <small class="text-blue-500">{{ $quiz->title }}</small></h2>
            </div>
            <div>
                <span class="text-sm font-normal">
                    Total Questions
                </span>
                <span class="px-2 py-1 rounded-full bg-blue-500 text-white text-xs">{{ $quiz->questions()->count() }}</span>
            </div>
        </div>
    </div>
    <div class="card-content">
        <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST" enctype="multipart/form-data">
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

            <markdown-editor name="text" value="{{ old('text') }}"></markdown-editor>

            <label class="control">File</label>
            <input type="file" class="control" name="illustrations[]" multiple>

            <question-type></question-type>

            <button class="btn is-blue mt-4" type="submit">Create</button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn">Cancel</a>
        </form>
    </div>
</div>
@endsection
