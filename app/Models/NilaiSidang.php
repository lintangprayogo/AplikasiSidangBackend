<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSidang extends Model
{
    use HasFactory;

    protected $table="nilai_sidang";
    protected $fillable = [
      "sidang_id",
      "nilai_laporan",
      "nilai_presentasi",
       "nilai_produk","sumber"  
     ]; 
     
     protected $casts = [
      'nilai_laporan' => 'integer',
      'nilai_presentasi' => 'integer',
      'nilai_produk' => 'integer'
  ];
}
