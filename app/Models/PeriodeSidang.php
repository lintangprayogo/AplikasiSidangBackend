<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeSidang extends Model
{
    use HasFactory;

    protected $table='periode_sidang';
    protected $fillable = [
        'periode_judul', 
        'periode_mulai', 
        'periode_akhir',
        'jalur_sidang', 
        'status'
        ];   
}
