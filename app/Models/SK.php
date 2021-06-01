<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SK extends Model
{
    use HasFactory;
    protected $table = "sk";
  
    protected $fillable = [
        'nomor_sk', 
        'sk_mhs_nim',
        'judul_indonesia', 
        'judul_inggris', 
        'informasi_waktu',
        'tanggal_persetujuan',
        'tanggal_kadaluarsa'        
        ];
   
        
    
        
}
