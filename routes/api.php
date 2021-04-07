<?php

use App\Http\Controllers\ApiController\ApiControllerAuth;
use App\Http\Controllers\ApiController\ApiControllerDosen;
use App\Http\Controllers\ApiController\ApiControllerMahasiswa;
use App\Http\Controllers\ApiController\ApiControllerInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::group(['prefix' => 'v1'], function () {
    
    Route::post('/user/signin', [ApiControllerAuth::class, 'signin']);

    Route::get('imagepath',function(){
        return Storage::url("dosen/default.jpg");
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('dosen', ApiControllerDosen::class);
        Route::resource('mahasiswa', ApiControllerMahasiswa::class);
        Route::resource('informasi', ApiControllerInformasi::class);
    });
 
    
    
    
  

});

