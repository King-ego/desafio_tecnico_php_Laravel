<?php

use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
//users endpoints
Route::namespace('Api')->name('users.')->prefix('users')->group(function () {

    Route::post('/', [UserController::class, 'store'])
        ->name('create');
    Route::get('/', [UserController::class, 'show'])
        ->name('show');//->middleware('auth:sanctum');
    Route::put('/', [UserController::class, 'update'])
        ->name('update');//->middleware('auth:sanctum');
    Route::delete('/delete-account', [UserController::class, 'destroy'])
        ->name('delete');//->middleware('auth:sanctum');
});
