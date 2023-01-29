<?php

use App\Http\Controllers\api\auth\AuthAdminController;
use App\Http\Controllers\api\auth\AuthUserController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin_api" middleware group. Enjoy building your API!
|
*/


// To get the authenticated Admin
Route::middleware('auth:sanctum')->get('/', function (Request $request) {
    return $request->user();
});


Route::post('auth/login',[AuthAdminController::class, "login"])->middleware("guest:sanctum");

Route::post('auth/register',[AuthAdminController::class, "register"])->middleware("guest:sanctum");

Route::post('auth/logout',[AuthAdminController::class, "logout"])->middleware("auth:sanctum");


