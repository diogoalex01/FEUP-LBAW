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

Route::post('/follow/{user_id}', 'UserController@follow');
Route::delete('/follow/{user_id}', 'UserController@unfollow');

Route::get('/notification', 'NotificationController@index')->name('notifications');

// Authentication
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// Google Authentication
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
Route::delete('/post/{post_id}', 'PostController@destroy');
Route::put('/post/{post_id}', 'PostController@update');
Route::post('/post/{post_id}/vote', 'PostController@vote')->name('post_vote');
Route::put('/post/{post_id}/vote', 'PostController@vote_edit')->name('post_edit_vote');
Route::delete('/post/{post_id}/vote', 'PostController@vote_delete')->name('post_delete_vote');

// Comment
Route::post('/comment', 'CommentController@store');
Route::post('/reply', 'CommentController@storeReply');
Route::delete('/comment/{comment_id}', 'CommentController@destroy');
Route::put('/comment/{comment_id}', 'CommentController@update');
Route::post('/comment/{comment_id}/vote', 'CommentController@vote')->name('comment_vote');
Route::put('/comment/{comment_id}/vote', 'CommentController@vote_edit')->name('comment_edit_vote');
Route::delete('/comment/{comment_id}/vote', 'CommentController@vote_delete')->name('comment_delete_vote');

// Admin
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {
    Route::get('/', 'AdminController@show')->name('home');

    Route::namespace('Auth')->group(function () {

        // Login Routes
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/login', 'LoginController@login');
        Route::post('/logout', 'LoginController@logout')->name('logout');
        
    });
});

// Search
Route::get('/search', 'SearchController@search_results')->name('search');
// Route::post('/search', 'SearchController@');
// TODO: autocomplete

// API
Route::post('/api/communities', 'CommunityController@get_all');
Route::get('/api/search', 'CommunityController@get_all');
Route::post('/api/home', 'PostController@refresh')->name('refresh_home');
Route::post('/api/community', 'CommunityController@refresh')->name('refresh_community');
Route::post('/api/homeTab', 'PostController@homeTab')->name('home_tab');
Route::post('/api/popularTab', 'PostController@popularTab')->name('popular_tab');
Route::post('/api/recentTab', 'PostController@recentTab')->name('recent_tab');

// Static Pages
Route::get('/about', 'PageController@about')->name('about');
Route::fallback('PageController@notFound404');
