<?php

namespace App\Models;
use App\Models\Judul;

use Illuminate\Database\Eloquent\Model;

class mahasiswa extends Model
{
    protected $table = "mahasiswa";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = "mhs_nim";

    protected $fillable = [
        'mhs_nim',
        'mhs_nama',
        'angkatan',
        'mhs_kontak',
        'mhs_foto',
        'mhs_email',
        'status',
        'username',
        'user_id'
        ];

  

    public function tbl_user()
    {
        return $this->hasOne('App\Models\user', 'username');
    }
}
