<?php

use App\Http\Controllers\API\LinkController as LinkControllerApi;
use App\Http\Controllers\API\AuthTokensController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->apiResource('/dashboard/links', LinkControllerApi::class)->names([]);

Route::middleware('auth:sanctum')->get('auth/tokens', [AuthTokensController::class, 'index']);
Route::middleware('guest:sanctum')->post('auth/tokens', [AuthTokensController::class, 'store']);
Route::middleware('auth:sanctum')->delete('auth/tokens/logout', [AuthTokensController::class, 'current_logout']);
Route::middleware('auth:sanctum')->delete('auth/tokens/{id}', [AuthTokensController::class, 'destroy']);
Route::middleware('auth:sanctum')->delete('auth/tokens/', [AuthTokensController::class, 'logout_all']);
