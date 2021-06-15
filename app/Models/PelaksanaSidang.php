<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelaksanaSidang extends Model
{
    use HasFactory;
    protected $table="pelaksana_sidang";
    protected $fillable = [
        'status', 
        'masa_aktif',
        'sk_mhs_nim',
        'pelaksana_dsn_nip', 
        'sk_id'
        ];
        
    public function dosen()
    {
        return Dosen::where("dsn_nip",$this->pelaksana_dsn_nip)->first();
    }

}
