<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSK extends Model
{
    use HasFactory;

    
    protected $table = "form_sk";
    public $timestamps = false;


    protected $fillable = [
        'form_sk_mhs_nim',
        'form_sk_mhs_nama',
        'form_sk_nip_1',
        'form_sk_dsn_nama_1',
        'form_sk_nip_2',
        'form_sk_dsn_nama_2',
        'form_sk_nip_new_1',
        'form_sk_dsn_nama_new_2',
        'form_sk_nip_new_2',
        'jenis',
        'judul_lama', 
        'judul_baru', 
        'alasan', 
        'persetujuan_pembimbing_1',
        'persetujuan_pembimbing_2',
        'persetujuan_pembimbing_new_1',
        'persetujuan_pembimbing_new_2',
        'sk_ta_lama',
        'persetujuan_prodi',
        'form_sk_dsn_nama_1',
        'form_sk_dsn_nama_2',
        ];

    
}
