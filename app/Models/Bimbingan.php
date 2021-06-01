<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    protected $table = "bimbingan";
    public $timestamps = false;


    protected $fillable = [
        'bimbingan_review',
        'bimbingan_kehadiran', 
        'bimbingan_tanggal', 
        'bimbingan_status', 
        'bimbingan_mhs_nim',
        'bimbingan_dsn_nip'
        ];

   /* public function getBimbinganTanggalAttribute($value)
    {
        return $this->tgl_indo($value);
    }

    public function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
     
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }*/

}
