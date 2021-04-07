<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = "dosen";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = "dsn_nip";


    protected $fillable = [
        'dsn_nip', 
        'dsn_nama',
        'dsn_kode', 
        'dsn_kontak', 
        'dsn_foto',
        'batas_bimbingan',
        'batas_penguji',
        'dsn_email',
        'user_id',
        ];

   
}

