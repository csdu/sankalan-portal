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
Route::get('/', 'PagesController@index')->name('homepage');

Auth::routes();

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::get('/teams', 'TeamController@index')->name('teams');
    Route::post('/teams', 'TeamController@store')->name('teams.store');
});