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

Route::get('/', 'PostController@list')->name('home');
Route::get('/new_post', 'PostController@create')->name('new_post');
Route::post('/new_post', 'PostController@store');

Route::get('/settings', 'UserController@edit')->name('settings');
Route::put('/settings', 'UserController@update');
Route::delete('/settings', 'UserController@destroy');

Route::get('/about', 'PageController@about')->name('about');

// API
Route::post('api/communities', 'CommunityController@get_all');

// Authentication
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('register', 'Auth\RegisterController@register')->name('register');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
