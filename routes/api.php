<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/logout', 'Api\AuthController@logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify_access', function () {
        return true;
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResources([
        'articles' => 'Api\ArticleController',
        'categories' => 'Api\CategoryController',
        'tags' => 'Api\TagController',
        'comments' => 'Api\CommentController',
    ]);
});

