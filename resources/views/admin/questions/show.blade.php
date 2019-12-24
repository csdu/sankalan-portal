@extends('layouts.admin')
@section('title', 'Quizzes')
@section('content')
<div class="card seperated">
    <div class="card-header">
        <div class="flex justify-between">
            <div class="flex">
                <h2 class="text-xl font-normal">
                    Question <small class="text-blue">{{ $question->qno }}</small>
                    of <small class="text-blue">{{ $quizTitle }}</small>
                </h2>
            </div>
            <div>
                <span class="text-sm font-normal">
                    Total Options
                </span>
                <span class="px-2 py-1 rounded-full bg-blue text-white text-xs">{{ $question->choices->count() }}</span>
            </div>
        </div>
    </div>
    <div class="px-6 py-4">
        {{ $question->text }}
    </div>
    <table class="w-full">
        <thead>
            <tr class="bg-grey-light">
                <th class="text-xs uppercase font-light text-left pl-6 py-2">Key</th>
                <th class="text-xs uppercase font-light text-left pr-6 py-2">Option</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($question->choices as $option)
                <tr class="border-t hover:bg-grey-lighter">
                    <td class="text-left pl-6 py-2">
                        {{ $option->key }}
                    </td>
                    <td class="text-left pr-6 py-2">
                        {{ $option->text }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">
        <p class="text-center text-grey-dark">That's all folks!</p>
    </div>
</div>
@endsection