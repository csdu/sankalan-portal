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

Route::get('events/create', 'EventController@create')
    ->name('admin.events.create');

Route::post('events', 'EventController@store')
    ->name('admin.events.store');

Route::get('events/{event}/edit', 'EventController@edit')
    ->name('admin.events.edit');

Route::patch('events/{event}', 'EventController@update')
    ->name('admin.events.update');

Route::delete('events/{event}/delete', 'EventController@delete')
    ->name('admin.events.delete');

Route::get('quizzes/create', 'QuizController@create')
    ->name('admin.quizzes.create');

Route::post('quizzes', 'QuizController@store')
    ->name('admin.quizzes.store');

Route::get('quizzes/{quiz}/edit', 'QuizController@edit')
    ->name('admin.quizzes.edit');

Route::patch('quizzes/{quiz}', 'QuizController@update')
    ->name('admin.quizzes.update');

Route::delete('quizzes/{quiz}/delete', 'QuizController@delete')
    ->name('admin.quizzes.delete');

Route::get('quizzes/{quiz}/show', 'QuizController@show')
    ->name('admin.quizzes.show');

Route::get('quizzes/{quiz}/questions/create', 'QuestionController@create')
    ->name('admin.quizzes.questions.create');

Route::post('quizzes/{quiz}/questions', 'QuestionController@store')
    ->name('admin.quizzes.questions.store');

Route::get('quizzes/{quiz}/questions/{question}/show', 'QuestionController@show')
    ->name('admin.quizzes.questions.show');

Route::delete('quizzes/{quiz}/questions/{question}/delete', 'QuestionController@delete')
    ->name('admin.quizzes.questions.delete');
