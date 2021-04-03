<?php

use App\Http\Controllers\Api\ApiControllerAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
    Route::get('/user',[ApiControllerAuth::class,'allUser']);
    Route::post('/user/signin', [ApiControllerAuth::class, 'signin']);
});
