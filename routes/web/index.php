<?php

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/', 'PagesController@index')->name('homepage')->middleware('guest');

Route::get('/help', 'PagesController@help')->name('help');
Route::get('/events', 'EventController@index')->name('events.index');

Route::middleware('auth')->group(base_path('routes/web/authUser.php'));

Route::middleware(['auth', 'admin'])
    ->namespace('Admin')
    ->prefix('manage')
    ->group(base_path('routes/web/admin.php'));
