<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'PagesController@index')->name('homepage')->middleware('guest');

Auth::routes();

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/teams', 'TeamController@index')->name('teams');
    Route::post('/teams', 'TeamController@store')->name('teams.store');
    Route::post('/events/{event}/participate', 'EventParticipationController@store')->name('events.participate');
    Route::delete('/events/{event}/participate', 'EventParticipationController@destroy')->name('events.withdraw-part');
    Route::get('/quiz/{quiz}', 'QuizController@show')->name('quizzes.take');
    Route::post('/quiz/{quiz}', 'QuizResponseController@store')->name('quizzes.response.store');
});

Route::group(['prefix' => 'manage', 'middleware' => 'admin', 'namespace' => 'Admin'], function() {
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::get('participations', 'EventParticipationController@index')->name('participations.index');
    Route::get('teams', 'TeamController@index')->name('teams.index');
    Route::get('users', 'UserController@index')->name('users.index');
    Route::get('events', 'EventController@index')->name('events.index');
    Route::post('events/{event}', 'EventController@goLive')->name('events.go-live');
});