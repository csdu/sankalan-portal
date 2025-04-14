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
                <h2 class="text-xl font-normal">Edit Question for <small class="text-blue">{{ $quiz->title }}</small></h2>
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
        <form action="{{ route('admin.quizzes.questions.update', [$quiz, $question]) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="flex -mx-2">
                <div class="w-1/3 px-2">
                    <label class="control">
                        Q.No.
                    </label>
                    <input class="control w-100" name="qno" type="number" value="{{ old('qno', $question->qno) }}" min="1" required>
                </div>

                <div class="w-1/3 px-2">
                    <label class="control">
                        Positive score
                    </label>
                    <input class="control" name="positive_score" min="1" type="number" value="{{ old('positive_score', $question->positive_score) }}" required>
                </div>

                <div class="w-1/3 px-2">
                    <label class="control">
                        Negative score
                    </label>
                    <input class="control" name="negative_score" min="0" type="number" value="{{ old('negative_score', $question->negative_score) }}" required>
                </div>
            </div>

            <p class="text-red font-normal my-1">WARNING: Please reformat question text</p>
            <markdown-editor name="text" value="{{ old('text', strip_tags($question->text)) }}"></markdown-editor>

            <button class="btn is-blue mt-4" type="submit">Update</button>
            <a href="{{ route('admin.quizzes.index') }}" class="btn">Cancel</a>
        </form>
    </div>
</div>
@endsection
