<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\AuthController@login');
Route::post('logout', 'Api\AuthController@logout');
Route::post('register', 'Api\AuthController@register');
Route::match(['get','post'],'settings', 'Api\App@settings');
Route::match(['get','post'],'current_program', 'Api\App@current_program');
Route::get('load_comments/{id}', 'Api\App@load_comments');
Route::get('notifications', 'Api\App@notifications');
Route::post('post_comments', 'Api\App@post_comments');
Route::post('forgot_my_password', 'Api\App@forgot_my_password');

Route::group(['prefix' => 'blog'], function ($router) {
    Route::get('popular_post', 'Api\App@getPopularBlogpost');
    Route::get('posts', 'Api\App@getPosts');
    Route::get('home', 'Api\App@gethome');
    Route::get('view/{id}', 'Api\App@viewpost');
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'Api\AuthController@login');
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('refresh', 'Api\AuthController@refresh');
    Route::post('register', 'Api\AuthController@register');
    Route::get('me', 'Api\AuthController@me');
});

Route::group(['middleware' => 'api','prefix' => 'setting'], function ($router) {
    Route::get('country', 'Api\App@countries');
    Route::get('state/{country_id}', 'Api\App@states');
    Route::get('programs', 'Api\App@programs');
});

Route::group(['middleware' => 'api','prefix' => 'fund'], function ($router) {
    Route::post('transfer', 'Api\App@transfer_fund');
    Route::get('transactions', 'Api\App@transactions');
    Route::post('payment_intent', 'Api\App@createPaymentIntents');
    Route::post('update_top_transaction', 'Api\App@update_top_transaction');
    Route::get('plan', 'Api\App@list_plan');
    Route::post('subscribe_to_plan', 'Api\App@subscribe_to_plan');
});

Route::group(['middleware' => 'api', 'prefix' => 'user'], function ($router) {
    Route::match(['post','get'],'status','Api\App@checkUserSub');
    Route::post('movie_request','Api\App@movie_request');
    Route::match(['post','get'],'delete_account','Api\App@delete_account');
});
