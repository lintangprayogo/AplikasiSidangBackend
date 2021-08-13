<?php

use App\Http\Controllers\ApiController\ApiControllerAuth;
use App\Http\Controllers\ApiController\ApiControllerBimbingan;
use App\Http\Controllers\ApiController\ApiControllerDosen;
use App\Http\Controllers\ApiController\ApiControllerMahasiswa;
use App\Http\Controllers\ApiController\ApiControllerInformasi;
use App\Http\Controllers\ApiController\DosenBimbinganController;
use App\Http\Controllers\ApiController\DosenFormSKController;
use App\Http\Controllers\ApiController\LakPeriodeSidangController;
use App\Http\Controllers\ApiController\MahasiswaFormSKController;
use App\Http\Controllers\ApiController\MahasiswaPendaftaranSidangController;
use App\Http\Controllers\ApiController\ProdiFormSKController;
use App\Http\Controllers\ApiController\ProfileController;
use App\Http\Controllers\ApiController\SidangMahasiswaController;
use App\Http\Controllers\ApiController\SidangProdiController;
use App\Http\Controllers\ApiController\SKController;
use App\Http\Controllers\ApiController\TugasAkhirController;
use App\Http\Controllers\ApiController\TugasAkhirDosenController;
use App\Http\Controllers\ApiController\SidangDosenController;
use Illuminate\Support\Facades\Route;




Route::group(['prefix' => 'v1'], function () {

   
   
    Route::post('/user/signin', [ApiControllerAuth::class, 'signin']);
  


    Route::middleware(['auth:sanctum'])->group(function () {


        Route::post('mahasiswa/photo',[ProfileController::class,"updatePhotoMahasiswa"]);
        Route::put('mahasiswa/profile',[ProfileController::class,"updateProfileMahasiswa"]);
        Route::get('formsk/mahasiswa/',[MahasiswaFormSKController::class,"index"]);
        
        Route::get('formsk/dosen/',[DosenFormSKController::class,"index"]);
        Route::get('formsk/dosen/accept/{id}',[DosenFormSKController::class,"formSKAccept"]);
        Route::get('formsk/dosen/reject/{id}',[DosenFormSKController::class,"formSKReject"]);
        Route::get('sidang/dosen/accept/{id}',[SidangDosenController::class,"sidangAccept"]);
        Route::get('sidang/dosen/reject/{id}',[SidangDosenController::class,"sidangReject"]);
        Route::post('sidang/dosen/nilai/{id}',[SidangDosenController::class,"sidangNilai"]);
        Route::get('sidang/dosen/jurnal/{id}', [SidangDosenController::class,"jurnalDownload"]);
        Route::get('sidang/dosen/revisi/{id}', [SidangDosenController::class,"revisiDownload"]);
        Route::resource('sidang/dosen', SidangDosenController::class);

        Route::get('formsk/mahasiswa/',[MahasiswaFormSKController::class,"index"]);
        Route::post('formsk/mahasiswa/extend',[MahasiswaFormSKController::class,"extendSK"]);
        Route::post('formsk/mahasiswa/change',[MahasiswaFormSKController::class,"changeSK"]);
        
        Route::get('formsk/prodi/',[ProdiFormSKController::class,"index"]);
        Route::get('formsk/prodi/accept/{id}',[ProdiFormSKController::class,"formSKAccept"]);
        Route::get('formsk/prodi/reject/{id}',[ProdiFormSKController::class,"formSKReject"]);
        Route::post('sidang/prodi/plot-sidang',[SidangProdiController::class,"plotSidang"]);
        Route::get('sidang/prodi/jurnal/{id}', [SidangProdiController::class,"jurnalDownload"]);
        Route::get('sidang/prodi/revisi/{id}', [SidangProdiController::class,"revisiDownload"]);
        Route::get('sidang/prodi/{id}', [SidangProdiController::class,"show"]);
        Route::resource('sidang/prodi/', SidangProdiController::class);

        Route::post('prodi/photo',[ProfileController::class,"updatePhotoProdi"]);
        Route::put('prodi/profile',[ProfileController::class,"updateProfileProdi"]);



        Route::resource('daftar-sidang/mahasiswa', MahasiswaPendaftaranSidangController::class);
        Route::post('sidang/mahasiswa/revisi/{id}', [SidangMahasiswaController::class,"uploadLembarRevisi"]);
        Route::get('sidang/mahasiswa/prediksi', [SidangMahasiswaController::class,"prediksiSidang"]);
        Route::get('sidang/mahasiswa/jurnal/{id}', [SidangMahasiswaController::class,"jurnalDownload"]);
        Route::get('sidang/mahasiswa/revisi/{id}', [SidangMahasiswaController::class,"revisiDownload"]);
        Route::resource('sidang/mahasiswa', SidangMahasiswaController::class);


        Route::resource('dosen', ApiControllerDosen::class);
        Route::post('dosen/photo',[ProfileController::class,"updatePhotoDosen"]);
        Route::put('dosen/profile',[ProfileController::class,"updateProfileDosen"]);
        
        Route::resource('mahasiswa', ApiControllerMahasiswa::class);
        Route::resource('informasi', ApiControllerInformasi::class);
        Route::get('sk/mahasiswa', [SKController::class,"showMahasiswa"]);
        Route::resource('sk', SKController::class);
        Route::get('tugas-akhir/download', [TugasAkhirController::class,"downloadSK"]);
        Route::resource('tugas-akhir', TugasAkhirController ::class);

        Route::get('tugas-akhir-dosen/download/{id}', [TugasAkhirDosenController::class,"downloadSK"]);
        Route::resource('tugas-akhir-dosen', TugasAkhirDosenController::class);


        Route::resource('periode-sidang/lak', LakPeriodeSidangController::class);

        Route::post('lak/photo',[ProfileController::class,"updatePhotoLak"]);
        Route::put('lak/profile',[ProfileController::class,"updateProfileLak"]);



        Route::resource('bimbingan-dosen', DosenBimbinganController::class);
        Route::get('bimbingan-dosen/accept/all', [DosenBimbinganController::class,"acceptAll"]);
        Route::get('bimbingan-dosen/accept/{id}', [DosenBimbinganController::class,"accept"]);
        Route::get('bimbingan-dosen/reject/{id}', [DosenBimbinganController::class,"reject"]);
        Route::resource('bimbingan', ApiControllerBimbingan::class);
        Route::post('logout', [ApiControllerAuth::class, 'logout']);
       
    });
 

});

