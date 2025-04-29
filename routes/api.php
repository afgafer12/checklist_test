<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\CheckListController;
use App\Http\Controllers\Api\CheckListItemController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', '\App\Http\Controllers\Api\AuthController@login');
Route::post('/register', '\App\Http\Controllers\Api\AuthController@register');
Route::get('/unauthorized', '\App\Http\Controllers\Api\AuthController@unauthorized')->name('unauthorized');

Route::middleware('auth:api')->group(function(){

    Route::prefix('/checklist')->controller(CheckListController::class)->group(function () {
        Route::get('/', 'getAll');
        Route::post('/', 'create');
        Route::delete('/{clId}', 'delete');
        //item
        Route::prefix('/{clId}/item')->controller(CheckListItemController::class)->group(function () {
            Route::get('/', 'getAll');
            Route::get('/{itemId}', 'getDetil');
            Route::post('/', 'create');
            Route::put('/rename/{itemId}', 'updateRename');
            Route::put('/{itemId}', 'updateStatus');
            Route::delete('/{itemId}', 'delete');
        });
    });

});