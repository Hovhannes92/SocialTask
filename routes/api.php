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

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('post', 'Api\PostController');
    Route::apiResource('post.comment', 'Api\CommentController');
    Route::apiResource('tag', 'Api\TagController');

    Route::post('/post/{post}/like', 'Api\PostController@like');
    Route::post('/post/{post}/dislike', 'Api\PostController@dislike');

    Route::post('/post/{post}/comment/{comment}/like', 'Api\CommentController@like');
    Route::post('/post/{post}/comment/{comment}/dislike', 'Api\CommentController@dislike');


});

Route::post('/login', 'Api\AuthController@login');

Route::post('/register', 'Api\AuthController@register');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




