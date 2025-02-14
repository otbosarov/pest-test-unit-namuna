<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [UserController::class, 'register']);

Route::middleware(["auth.sanctum"])->post('blog/create', [BlogController::class, 'store']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource("blogs", BlogController::class);
    Route::resource("users", UserController::class);
});
