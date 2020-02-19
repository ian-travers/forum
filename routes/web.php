<?php

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/threads', 'ThreadsController@index');
//Route::get('/threads/create', 'ThreadsController@create');
//Route::post('/threads', 'ThreadsController@store');
//Route::get('/threads/{thread}', 'ThreadsController@show');
Route::resource('threads', 'ThreadsController');
Route::post('/threads/{thread}/replies', 'RepliesController@store');
