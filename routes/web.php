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

//Route::get('/home', 'Auth\LoginController@home');
// Cardss
// Route::get('cards', 'CardController@list');
// Route::get('cards/{id}', 'CardController@show');

// API
// Route::put('api/cards', 'CardController@create');
// Route::delete('api/cards/{card_id}', 'CardController@delete');
// Route::put('api/cards/{card_id}/', 'ItemController@create');
// Route::post('api/item/{id}', 'ItemController@update');
// Route::delete('api/item/{id}', 'ItemController@delete');

// Authentication
// Route::get('login', 'Auth\LoginController@showLoginForm')
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
// Route::get('register', 'Auth\RegisterController@showRegistrationForm')
Route::post('register', 'Auth\RegisterController@register')->name('register');
