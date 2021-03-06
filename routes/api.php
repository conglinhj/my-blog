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

Route::get('/articles', 'Api\ArticleController@getList');
Route::get('/articles/{id}', 'Api\ArticleController@getDetail');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify_access', function () {
        return true;
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::namespace('Api\Resources')->prefix('resources')->name('resources')->group(function () {
        Route::apiResources([
            'articles' => 'ArticleResourceController',
            'categories' => 'CategoryResourceController',
            'tags' => 'TagResourceController',
            'comments' => 'CommentResourceController',
        ]);

        Route::prefix('articles')->group(function () {
            Route::post('publish/{id}', 'ArticleResourceController@publish');
            Route::post('draft/{id}', 'ArticleResourceController@draft');
            Route::post('bulk', 'ArticleResourceController@bulkAction');
        });

        Route::prefix('categories')->group(function () {
            Route::get('{id}/able-parents', 'CategoryResourceController@getAbleParents');
        });
    });
});

