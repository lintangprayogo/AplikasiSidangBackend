<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Sidang;
use App\Models\NilaiSidang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\PelaksanaSidang;

class BeritaAcaraExport implements FromView
{
   

   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $sidangs = Sidang::join('sk', 'sk.id', "=", "sidang.sk_id")
        ->join('periode_sidang', 'periode_sidang.id', "=", "sidang.periode_id")
        ->join('mahasiswa', 'sk.sk_mhs_nim', "=", "mahasiswa.mhs_nim")
        ->select(
          "sidang.id as id",
          "mhs_nama",
          "mhs_nim",
          "jalur_sidang",
          "periode_judul",
          "tanggal_sidang",
          "judul_indonesia",
          "sk.id as sk_id"
        )->get();

       
        $ids = $sidangs->pluck('id');
        $sk_ids = $sidangs->pluck('sk_id');
   
        $nilai_sidang=NilaiSidang::whereIn("sidang_id",$ids)->get()->groupBy('sumber');
        $nilai_pembimbing_1=[];
        $nilai_pembimbing_2=[];
        $nilai_penguji_1=[];
        $nilai_penguji_2=[];

        $pembimbing_1 = PelaksanaSidang::where("status", "=", "PEMBIMBING1")->whereIn("sk_id",$sk_ids)
        ->get();
        $pembimbing_2 = PelaksanaSidang::where("status", "=", "PEMBIMBING2")->whereIn("sk_id",$sk_ids)
        ->get();
        $penguji_1 = PelaksanaSidang::where("status", "=", "PENGUJI1")->whereIn("sk_id",$sk_ids)
        ->get();
        $penguji_2 = PelaksanaSidang::where("status", "=", "PENGUJI2")->whereIn("sk_id",$sk_ids)
        ->get();
        
        if(isset($nilai_sidang["PEMBIMBING1"])){
            $nilai_pembimbing_1=$nilai_sidang["PEMBIMBING1"];
        }
        if(isset($nilai_sidang["PEMBIMBING2"])){
            $nilai_pembimbing_2=$nilai_sidang["PEMBIMBING2"];
        }
        if(isset($nilai_sidang["PENGUJI1"])){
            $nilai_penguji_1=$nilai_sidang["PENGUJI1"];
        }
        if(isset($nilai_sidang["PENGUJI2"])){
            $nilai_penguji_2=$nilai_sidang["PENGUJI2"];
        }

        foreach ($sidangs as $sidang){
            $sidang->jml_pembimbing=0;
            $sidang->jml_penguji=0;

            for  ($i = 0; $i < count($nilai_pembimbing_1); $i++){
                $nilai=$nilai_pembimbing_1[$i];
                if($nilai->sidang_id==$sidang->id){
                    $sidang->nilai_pembimbing_1=$nilai;
                    unset($nilai_pembimbing_1[$i]);
                }
            }
            
            for  ($i = 0; $i < count($nilai_pembimbing_2); $i++){
                $nilai=$nilai_pembimbing_2[$i];
                if($nilai->sidang_id==$sidang->id){
                    $sidang->nilai_pembimbing_2=$nilai;
                    unset($nilai_pembimbing_2[$i]);
                }
            }

            for  ($i = 0; $i < count($nilai_penguji_1); $i++){
                $nilai=$nilai_penguji_1[$i];
                if($nilai->sidang_id==$sidang->id){
                    $sidang->nilai_penguji_1=$nilai;
                    unset($nilai_penguji_1[$i]);
                }
            }
            
            for  ($i = 0; $i < count($nilai_penguji_2); $i++){
                $nilai=$nilai_penguji_2[$i];
                if($nilai->sidang_id==$sidang->id){
                    $sidang->nilai_penguji_2=$nilai;
                    unset($nilai_penguji_2[$i]);
                }
            }
           
       

            for  ($i = 0; $i < count($pembimbing_1); $i++){
                if($pembimbing_1[$i]->sk_id==$sidang->sk_id){
                    $sidang->jml_pembimbing=  $sidang->jml_pembimbing++;
                }
            }
            
            for  ($i = 0; $i < count($pembimbing_2); $i++){
                 if($pembimbing_2[$i]->sk_id==$sidang->sk_id){
                    $sidang->jml_pembimbing=  $sidang->jml_pembimbing++;
                }
            }

            for  ($i = 0; $i < count($penguji_1); $i++){
                if($penguji_1[$i]->sk_id==$sidang->sk_id&&$penguji_1[$i]->tanggal_sidang==$sidang->tanggal_sidang){
                    $sidang->jml_penguji=  $sidang->jml_penguji++;
                }
            }

            for  ($i = 0; $i < count($penguji_2); $i++){
                if($penguji_2[$i]->sk_id==$sidang->sk_id&&$penguji_2[$i]->tanggal_sidang==$sidang->tanggal_sidang){
                    $sidang->jml_penguji=  $sidang->jml_penguji++;
                }
            }
            
            $sidang->nilai_akhir_total=$this->nilaiSidang($sidang->nilai_pembimbing_1,$sidang->nilai_pembimbing_2,
            $sidang->nilai_penguji_1,$sidang->nilai_penguji_2,$sidang->jml_pembimbing,$sidang->jml_penguji);
            $sidang->index_nilai=$this->indexNilai($sidang->nilai_akhir_total);
        }
        return view('berita-acara-excel', [
            'sidangs' => $sidangs
        ]);
       
    }

    public function nilaiSidang($nilai_pembimbing_1,$nilai_pembimbing_2,$nilai_penguji_1,$nilai_penguji_2, $jml_pembimbing,$jml_penguji){
    
        
    $nilai_pembimbing1_laporan=null;
    $nilai_pembimbing1_presentasi=null;
    $nilai_pembimbing1_produk=null;

    $nilai_pembimbing2_laporan=null;
    $nilai_pembimbing2_presentasi=null;
    $nilai_pembimbing2_produk=null;

    $nilai_penguji1_laporan=null;
    $nilai_penguji1_presentasi=null;
    $nilai_penguji1_produk=null;

    $nilai_penguji2_laporan=null;
    $nilai_penguji2_presentasi=null;
    $nilai_penguji2_produk=null;

    if($nilai_pembimbing_1){
        $nilai_pembimbing1_laporan=$nilai_pembimbing_1->nilai_laporan;
        $nilai_pembimbing1_presentasi=$nilai_pembimbing_1->nilai_presentasi;
        $nilai_pembimbing1_produk=$nilai_pembimbing_1->nilai_produk;
        $jml_pembimbing++;
    }

    if($nilai_pembimbing_2){
        $nilai_pembimbing2_laporan=$nilai_pembimbing_2->nilai_laporan;
        $nilai_pembimbing2_presentasi=$nilai_pembimbing_2->nilai_presentasi;
        $nilai_pembimbing2_produk=$nilai_pembimbing_2->nilai_produk;
        $jml_pembimbing++;
    }

    if($nilai_penguji_1){
        $nilai_penguji1_laporan=$nilai_penguji_1->nilai_laporan;
        $nilai_penguji1_presentasi=$nilai_penguji_1->nilai_presentasi;
        $nilai_penguji1_produk=$nilai_penguji_1->nilai_produk;
        $jml_penguji++;
    }

    if($nilai_penguji_2){
        $nilai_penguji2_laporan=$nilai_penguji_2->nilai_laporan;
        $nilai_penguji2_presentasi=$nilai_penguji_2->nilai_presentasi;
        $nilai_penguji2_produk=$nilai_penguji_2->nilai_produk;
        $jml_penguji++;
    }

    //pembimbing
    //Laporan
    if ($nilai_pembimbing2_laporan && $nilai_pembimbing1_laporan) {
        $ra_laporan =
          ($nilai_pembimbing1_laporan + $nilai_pembimbing2_laporan) / $jml_pembimbing;
      } else if ($nilai_pembimbing1_laporan) {
        $ra_laporan = $nilai_pembimbing1_laporan / $jml_pembimbing;
      } else if ($nilai_pembimbing2_laporan) {
        $ra_laporan = $nilai_pembimbing2_laporan / $jml_pembimbing;
      }
  
      //presentasi
      if ($nilai_pembimbing1_presentasi && $nilai_pembimbing2_presentasi) {
        $ra_presentasi =
          ($nilai_pembimbing1_presentasi + $nilai_pembimbing2_presentasi) / $jml_pembimbing;
      } else if ($nilai_pembimbing1_presentasi) {
        $ra_presentasi = $nilai_pembimbing1_presentasi / $jml_pembimbing;
      } else if ($nilai_pembimbing2_presentasi) {
        $ra_presentasi = $nilai_pembimbing2_presentasi / $jml_pembimbing;
      }
  
      //produk
      if ($nilai_pembimbing1_produk && $nilai_pembimbing2_produk) {
        $ra_produk =
          ($nilai_pembimbing1_produk + $nilai_pembimbing2_produk) / $jml_pembimbing;
      } else if ($nilai_pembimbing1_produk) {
        $ra_produk = $nilai_pembimbing1_produk / $jml_pembimbing;
      } else if ($nilai_pembimbing2_produk) {
        $ra_produk = $nilai_pembimbing2_produk / $jml_pembimbing;
      }
  
  
      //penguji
      if ($nilai_penguji2_laporan && $nilai_penguji1_laporan) {
        $rb_laporan =
          ($nilai_penguji1_laporan + $nilai_penguji2_laporan) / $jml_penguji;
      } else if ($nilai_penguji1_laporan) {
        $rb_laporan = $nilai_penguji1_laporan / $jml_penguji;
      } else if ($nilai_penguji2_laporan) {
        $rb_laporan = $nilai_penguji2_laporan / $jml_penguji;
      }
  
      if ($nilai_penguji1_presentasi && $nilai_penguji2_presentasi) {
        $rb_presentasi =
          ($nilai_penguji1_presentasi + $nilai_penguji2_presentasi) / $jml_penguji;
      } else if ($nilai_penguji1_presentasi) {
        $rb_presentasi = $nilai_penguji1_presentasi / $jml_penguji;
      } else if ($nilai_penguji2_presentasi) {
        $rb_presentasi = $nilai_penguji2_presentasi / $jml_penguji;
      }
  
      if ($nilai_penguji1_produk && $nilai_penguji2_produk) {
        $rb_produk =
          ($nilai_penguji1_produk + $nilai_penguji2_produk) / $jml_penguji;
      } else if ($nilai_penguji1_produk) {
        $rb_produk = $nilai_penguji1_produk / $jml_penguji;
      } else if ($nilai_penguji2_produk) {
        $rb_produk = $nilai_penguji2_produk / $jml_penguji;
      }
  
      if ($ra_laporan && $rb_laporan) {
        $rt_laporan = $ra_laporan * 0.6 + $rb_laporan * 0.4;
      } else if ($ra_laporan) {
        $rt_laporan = $ra_laporan * 0.6;
      } else if ($rb_laporan) {
        $rt_laporan = $rb_laporan * 0.4;
      }
  
      if ($ra_presentasi && $rb_presentasi) {
  
        $rt_presentasi = $ra_presentasi * 0.6 + $rb_presentasi * 0.4;
      } else if ($ra_presentasi) {
        $rt_presentasi = $ra_presentasi * 0.6;
      } else if ($rb_presentasi) {
        $rt_presentasi = $rb_presentasi * 0.4;
      }
  
      if ($ra_produk && $rb_produk) {
        $rt_produk = $ra_produk * 0.6 + $rb_produk * 0.4;
      } else if ($ra_produk) {
        $rt_produk = $ra_produk * 0.6;
      } else if ($rb_produk) {
        $rt_produk = $rb_produk * 0.4;
      }
  
      $na_laporan = $rt_laporan * 0.35;
      $na_presentasi = $rt_presentasi * 0.30;
      $na_produk = $rt_produk * 0.35;
  
  
      $na_total = $na_laporan + $na_presentasi + $na_produk;
      $na_total = number_format($na_total, 2);
  
      $rt_laporan = number_format($rt_laporan, 2);
      $rt_presentasi = number_format($rt_presentasi, 2);
      $rt_produk = number_format($rt_produk, 2);
  
      $ra_laporan = number_format($ra_laporan, 2);
      $ra_presentasi = number_format($ra_presentasi, 2);
      $ra_produk = number_format($ra_produk, 2);
  
      $rb_laporan = number_format($rb_laporan, 2);
      $rb_presentasi = number_format($rb_presentasi, 2);
      $rb_produk = number_format($rb_produk, 2);
  
      $na_laporan = number_format($na_laporan, 2);
      $na_presentasi = number_format($na_presentasi, 2);
      $na_produk = number_format($na_produk, 2);
      return  $na_total;
    }

    private function indexNilai($nilai){

        if($nilai>=80){
          return "A";
        }else if($nilai>70 && $nilai<=80 ){
            return "AB";
        }else if($nilai>65 && $nilai<=70 ){
            return "B";
        }
        else if($nilai>60 && $nilai<=65 ){
            return "BC";
        } else if($nilai>50 && $nilai<=60 ){
            return "C";
        }else if($nilai>40 && $nilai<=50 ){
            return "D";
        }else {
            return "E";
        }
    }
}