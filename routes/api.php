<?php

use App\Http\Controllers\ApiController\ApiControllerAuth;
use App\Http\Controllers\ApiController\ApiControllerBimbingan;
use App\Http\Controllers\ApiController\ApiControllerDosen;
use App\Http\Controllers\ApiController\ApiControllerMahasiswa;
use App\Http\Controllers\ApiController\ApiControllerInformasi;
use App\Http\Controllers\ApiController\DosenBimbinganController;
use App\Http\Controllers\ApiController\DosenFormSKController;
use App\Http\Controllers\ApiController\MahasiswaFormSKController;
use App\Http\Controllers\ApiController\ProfileController;
use App\Http\Controllers\ApiController\SidangMahasiswaController;
use App\Http\Controllers\ApiController\SKController;
use App\Http\Controllers\ApiController\TugasAkhirController;
use App\Http\Controllers\ApiController\TugasAkhirDosenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::group(['prefix' => 'v1'], function () {
    
    Route::post('/user/signin', [ApiControllerAuth::class, 'signin']);

    Route::get('imagepath',function(){
        return Storage::url("dosen/default.jpg");
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('mahasiswa/photo',[ProfileController::class,"updatePhotoMahasiswa"]);
        Route::put('mahasiswa/profile',[ProfileController::class,"updateProfileMahasiswa"]);
        Route::get('formsk/mahasiswa/',[MahasiswaFormSKController::class,"index"]);
        Route::get('formsk/dosen/',[DosenFormSKController::class,"index"]);
        Route::get('formsk/dosen/accept/{id}',[DosenFormSKController::class,"formSKAccept"]);
        Route::get('formsk/dosen/reject/{id}',[DosenFormSKController::class,"formSKRejec"]);
        Route::get('formsk/mahasiswa/',[MahasiswaFormSKController::class,"index"]);
        Route::post('formsk/mahasiswa/extend',[MahasiswaFormSKController::class,"extendSK"]);
        
        Route::resource('dosen', ApiControllerDosen::class);
        Route::post('dosen/photo',[ProfileController::class,"updatePhotoDosen"]);
        Route::put('dosen/profile',[ProfileController::class,"updateProfileDosen"]);
        Route::resource('mahasiswa', ApiControllerMahasiswa::class);
        Route::resource('informasi', ApiControllerInformasi::class);
        Route::get('sk/mahasiswa', [SKController::class,"showMahasiswa"]);
        Route::resource('sk', SKController::class);
        Route::resource('tugas-akhir', TugasAkhirController ::class);
        Route::resource('tugas-akhir-dosen', TugasAkhirDosenController::class);
        Route::resource('bimbingan-dosen', DosenBimbinganController::class);
        Route::get('bimbingan-dosen/accept/all', [DosenBimbinganController::class,"acceptAll"]);
        Route::get('bimbingan-dosen/accept/{id}', [DosenBimbinganController::class,"accept"]);
        Route::get('bimbingan-dosen/reject/{id}', [DosenBimbinganController::class,"reject"]);
        Route::resource('bimbingan', ApiControllerBimbingan::class);
        Route::post('logout', [ApiControllerAuth::class, 'logout']);
        Route::get('sidang/mahasiswa/prediksi', [SidangMahasiswaController::class,"prediksiSidang"]);
    });
 

});

