<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lak extends Model
{
   protected $table = "lak";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = "lak_nip";


    protected $fillable = [
        'lak_nip', 
        'lak_nama',
        'lak_kode', 
        'lak_kontak', 
        'lak_foto',
        'lak_email',
        'user_id',
        ];
    
}
