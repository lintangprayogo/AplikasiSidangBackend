<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\SK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SidangMahasiswaController extends Controller
{
    
    public function prediksiSidang(){
        $mahasiswa=Auth::user()->mahasiswa;
        $sk=SK::where("sk_mhs_nim",$mahasiswa->mhs_nim)->first();
        $originalDate=$sk->tanggal_persetujuan;
        $sidangTerjadwalDate1 = date('Y-m-d', strtotime('+1 month', strtotime($originalDate)));
        $sidangTerjadwalDate2 = date('Y-m-d', strtotime('+12 month', strtotime($originalDate)));

        $sidangTejadwalObj1=(object) [
            'judul' => 'Prediksi Sidang 1',
            'tanggal' => $sidangTerjadwalDate1,
          ];

          $sidangTerjadwalObj2=(object) [
            'judul' => 'Prediksi Sidang 2',
            'tanggal' => $sidangTerjadwalDate2,
          ];
       $reponse=array($sidangTejadwalObj1,$sidangTerjadwalObj2);

       return ResponseFormatter::success($reponse,"data succsess retrived !!");
    }
}
