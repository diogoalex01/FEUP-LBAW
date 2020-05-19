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

// User
Route::get('/user/{user_id}', 'UserController@show')->name('profile');
Route::get('/settings', 'UserController@edit')->name('settings');
Route::put('/settings', 'UserController@update');
Route::delete('/settings', 'UserController@destroy');

// Authentication
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/register', 'Auth\RegisterController@register')->name('register');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');

// Reset Password
Route::put('/reset_password_form', 'AccountsController@validatePasswordRequest');
Route::post('/reset_password_token', 'AccountsController@resetPassword');

Route::get('/reset_password_email_sent', 'AccountsController@verifyEmail');
Route::get('/reset/{token}', 'AccountsController@reset');

// Home
Route::get('/', 'PostController@list')->name('home');

// Community
Route::get('/community/{community_id}', 'CommunityController@show')->name('community');

// New Post 
Route::get('/new_post', 'PostController@create')->name('new_post');
Route::post('/new_post', 'PostController@store');

// Post 
Route::get('/post/{post_id}', 'PostController@show')->name('post');
Route::post('/post/{post_id}/vote', 'PostController@vote')->name('post_vote');
Route::put('/post/{post_id}/vote', 'PostController@vote_edit')->name('post_edit_vote');
Route::delete('/post/{post_id}/vote', 'PostController@vote_delete')->name('post_delete_vote');

// Comment
Route::put('/comment', 'CommentController@store');
Route::put('/reply', 'CommentController@storeReply');
Route::post('/comment/{comment_id}/vote', 'CommentController@vote')->name('comment_vote');
Route::put('/comment/{comment_id}/vote', 'CommentController@vote_edit')->name('comment_edit_vote');
Route::delete('/comment/{comment_id}/vote', 'CommentController@vote_delete')->name('comment_delete_vote');

// Search
Route::get('/search/{query}', 'CommunityController@get_all');
Route::post('/search', 'CommunityController@get_all');

// API
Route::post('/api/communities', 'CommunityController@get_all');
Route::get('/api/search', 'CommunityController@get_all');
Route::post('/api/home', 'PostController@refresh')->name('refresh_home');
Route::post('/api/community', 'CommunityController@refresh')->name('refresh_community');

// Static Pages
Route::get('/about', 'PageController@about')->name('about');
