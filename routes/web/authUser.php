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

Route::get('/quizzes/{quiz}', 'QuizController@instructions')
    ->name('quizzes.show');

Route::post('/quizzes/{quiz}', 'QuizResponseController@store')
    ->name('quizzes.response.store');

Route::post('/quizzes/{quiz}/take', 'QuizController@show')
    ->name('quizzes.take');
