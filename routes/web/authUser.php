<?php

Route::get('/dashboard', 'DashboardController@index')
    ->name('dashboard');

Route::get('/teams', 'TeamController@index')
    ->name('teams');

Route::post('/teams', 'TeamController@store')
    ->name('teams.store');

Route::post(
    '/events/{event}/participate',
    'EventParticipationController@store'
)->name('events.participate');

Route::delete(
    '/events/{event}/participate',
    'EventParticipationController@destroy'
)->name('events.withdraw-part');

Route::middleware('quiz_token_verified')->group(function () {
    Route::get('/quizzes/{quiz}', 'QuizController@instructions')
    ->name('quizzes.show');

    Route::post('/quizzes/{quiz}', 'QuestionResponseController@store')
    ->name('quizzes.response.store');

    Route::post('/quizzes/{quiz}/saveQuestionResponse', 'QuestionResponseController@saveQuestionResponse')
    ->name('quizzes.response.save');

    Route::get('/quizzes/{quiz}/take', 'QuizController@show')
    ->name('quizzes.take');
});

Route::get('/quizzes/{quiz}/verify', 'QuizVerificationController@showVerificationForm')
    ->name('quizzes.verify');

Route::post('/quizzes/{quiz}/verify', 'QuizVerificationController@verify')
->name('quizzes.verify');

Route::get('/question_attachments/{attachment}', 'QuestionAttachmentsController@show');
