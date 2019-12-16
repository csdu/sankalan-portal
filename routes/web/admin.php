<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', 'DashboardController@index')
    ->name('admin.dashboard');

Route::get('events_teams/{event?}', 'EventTeamController@index')
    ->name('admin.events.teams.index');

Route::get('teams', 'TeamController@index')
    ->name('admin.teams.index');

Route::get('users', 'UserController@index')
    ->name('admin.users.index');

Route::get('events', 'EventController@index')
    ->name('admin.events.index');

Route::post('events/{event}/start', 'EventController@goLive')
    ->name('admin.events.go-live');

Route::post('events/{event}/end', 'EventController@end')
    ->name('admin.events.end');

Route::post(
    'events/{event}/teams/{team}/paticipate-active-quiz',
    'QuizResponseController@store'
)->name('admin.events.teams.allow-active-quiz');

Route::get('quizzes', 'QuizController@index')
    ->name('admin.quizzes.index');

Route::post('quizzes/{quiz}/open', 'QuizController@goLive')
    ->name('admin.quizzes.go-live');

Route::post('quizzes/{quiz}/close', 'QuizController@close')
    ->name('admin.quizzes.close');

Route::post('quizzes/{quiz}/evaluate', 'QuizController@evaluate')
    ->name('admin.quizzes.evaluate');

Route::get('quizzes_teams/{quiz?}', 'QuizResponseController@index')
    ->name('admin.quizzes.teams.index');

Route::post(
    'quizzes_teams/{quiz_response}/evaluate',
    'QuizResponseController@evaluate'
)->name('admin.quizzes.teams.evaluate');

Route::get(
    'quiz_responses/{quiz_response}',
    'QuizResponseController@show'
)->name('admin.quiz-participations.show');

Route::post('events', 'EventController@add')
    ->name('admin.events.add');

Route::delete('events/{event}/delete', 'EventController@delete')
    ->name('admin.events.delete');
