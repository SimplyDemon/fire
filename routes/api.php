<?php

use App\Http\Controllers\Rest\CategoryController;
use App\Http\Controllers\Rest\ProductController;
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

Route::middleware( 'auth:api' )->get( '/user', function ( Request $request ) {
    return $request->user();
} );

Route::prefix( 'v1' )->name( 'rest.' )->group( function () {
    Route::apiResource( 'products', ProductController::class )->only( [
        'index',
        'store',
        'update',
        'destroy',
        'show'
    ] );

    Route::apiResource( 'categories', CategoryController::class )->only( [
        'index',
        'store',
        'update',
        'destroy',
        'show'
    ] );
} );
