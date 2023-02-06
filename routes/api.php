<?php

use App\Http\Controllers\api\auth\AuthAdminController;
use App\Http\Controllers\api\auth\AuthUserController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


// User Authentication ----------------------------------------------------------------
Route::group(["middleware"=>["guest:sanctum"]], function (){
    Route::post('/auth/login',[AuthUserController::class, "login"]);
    Route::post('/auth/register',[AuthUserController::class, "register"]);

});

    //Logout
Route::group(["middleware"=>["auth:sanctum"]], function (){
    Route::post('/auth/logout',[AuthUserController::class, "logout"]);
});
// ----------------------------------------------------------------



// Product Routes ----------------------------------------------------------------


Route::apiResource("/products", ProductController::class)->only("index");

Route::post("/products/update/{id}", [ProductController::class, "updateProduct"])->middleware("auth:sanctum");

Route::apiResource("/products", ProductController::class)->except("index")->middleware("auth:sanctum");

// -------------------------------------------------------------------------------

// Cart Routes ----------------------------------------------------------------

Route::delete("/cart/empty", [CartController::class, "empty"]);
Route::post("/cart/total", [CartController::class, "total"]);
Route::apiResource("/cart", CartController::class);
// ----------------------------------------------------------------------------
