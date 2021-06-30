<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    
    protected $table = "prodi";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = "prd_nip";


    protected $fillable = [
        'prd_nip', 
        'prd_nama',
        'prd_kode', 
        'prd_kontak', 
        'prd_foto',
        'prd_email',
        'user_id',
        ];
    
}
