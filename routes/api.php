<?php

use App\Http\Controllers\api\auth\AuthUserController;
use App\Http\Controllers\api\ProductController;
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



Route::group(["middleware"=>["guest:sanctum"]], function (){
    Route::post('/auth/login',[AuthUserController::class, "login"]);
    Route::post('/auth/register',[AuthUserController::class, "register"]);

});

Route::group(["middleware"=>["auth:sanctum"]], function (){
    Route::post('/auth/logout',[AuthUserController::class, "logout"]);
});





Route::apiResource("/products", ProductController::class);

Route::apiResource("/products", ProductController::class)->only("index")->middleware("auth:sanctum");



