<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\PelaksanaSidang;
use App\Models\SK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
class TugasAkhirDosenController extends Controller
{
    public function index(){
        $dosen=Auth::user()->dosen;
        $sk_ids=PelaksanaSidang::where("pelaksana_dsn_nip","=",$dosen->dsn_nip)
        ->pluck("sk_id");
        if(!$dosen){
            return ResponseFormatter::error(
                [
                    'message' =>"You Are Not A Teacher",
                    'error' => []
                ],
                "You Are Not A Teacher",
                403,
            );
        }

        $rawData=SK::join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->join('pelaksana_sidang','pelaksana_sidang.sk_id',"=","sk.id")
        ->join('dosen','dosen.dsn_nip',"=","pelaksana_sidang.pelaksana_dsn_nip")
        ->select("sk.id as sk_id","mahasiswa.mhs_nama","sk.judul_indonesia","sk.judul_inggris",
        "pelaksana_sidang.status as status_pembimbing","dosen.dsn_nama",
        "mahasiswa.mhs_nim",
        "dosen.dsn_nip","tanggal_persetujuan",
        "tanggal_kadaluarsa","nomor_sk")
        ->orderBy("mhs_nim")->whereIn('sk_id', $sk_ids)
        ->get()->groupBy('status_pembimbing');

        $response=[];
        if(count($rawData)==0){
            return ResponseFormatter::success(
                $response,
                "Data Successfully Retrived"
            );
        }

        $pembimbing1=$rawData["PEMBIMBING1"];
        $pembimbing2=[];
        if (property_exists("PEMBIMBING2",$rawData)){
            $pembimbing2= $rawData["PEMBIMBING2"];
        }

      
        
 
        foreach($pembimbing1 as $data1){
            $object = (object) [
                'sk_id' => $data1->sk_id,
                "nomor_sk"=>$data1->nomor_sk,
                'judul_indonesia' =>str_replace("\n","",$data1->judul_indonesia) ,
                'judul_inggris' =>str_replace("\n","", $data1->judul_inggris) ,
                'mhs_nim'=>$data1->mhs_nim,
                'mhs_nama'=>$data1->mhs_nama,
                'nip_pembimbing1'=>$data1->dsn_nip,
                'nama_pembimbing1'=>$data1->dsn_nama,
                'nip_pembimbing2'=>null,
                'nama_pembimbing2'=>null,
                'tanggal_persetujuan'=>$this->tgl_indo( $data1->tanggal_persetujuan),
                'tanggal_kadaluarsa'=>$this->tgl_indo( $data1->tanggal_kadaluarsa),
                'status'=>$this->getStatus( $data1->tanggal_kadaluarsa),
                
              ];
              for($i=0; $i<count($pembimbing2); $i++ ){
                  $data2=$pembimbing2[$i];
                     if($data2->sk_id==$data1->sk_id){
                        $object->nip_pembimbing2=$data2->dsn_nip;
                        $object->nama_pembimbing2=$data2->dsn_nama;
                     }
              }
           array_push($response,$object);
        }

        
        return ResponseFormatter::success(
            $response,
            "Data Successfully Retrived"
        );
       
    }


    public function show($id){
        $dosen=Auth::user()->dosen;
        $rawData=SK::join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->join('pelaksana_sidang','pelaksana_sidang.sk_id',"=","sk.id")
        ->join('dosen','dosen.dsn_nip',"=","pelaksana_sidang.pelaksana_dsn_nip")
        ->select("sk.id as sk_id","mahasiswa.mhs_nama","sk.judul_indonesia","sk.judul_inggris",
        "pelaksana_sidang.status as status_pembimbing","dosen.dsn_nama",
        "mahasiswa.mhs_nim",
        "dosen.dsn_nip","tanggal_persetujuan",
        "tanggal_kadaluarsa","nomor_sk")->where('sk.id','=',$id)
        ->orderBy("mhs_nim")->get()->groupBy('status_pembimbing');
      
        if(count($rawData)==0){
         return ResponseFormatter::success(
            null,
            "Data Successfully Retrived"
        );
        }
        $pembimbing1=null;
        if(count($rawData["PEMBIMBING1"])>0){
            $pembimbing1=$rawData["PEMBIMBING1"][0];
         }
 
        $pembimbing2=null;
        if(count($rawData["PEMBIMBING2"])>0){
           $pembimbing2=$rawData["PEMBIMBING2"][0];
        }


        $response = (object) [
            'sk_id' => $pembimbing1->sk_id,
            'nomor_sk'=> $pembimbing1->nomor_sk,
            'judul_indonesia' =>str_replace("\n","",$pembimbing1->judul_indonesia) ,
            'judul_inggris' =>str_replace("\n","", $pembimbing1->judul_inggris) ,
            'mhs_nim'=>$pembimbing1->mhs_nim,
            'mhs_nama'=>$pembimbing1->mhs_nama,
            'nip_pembimbing1'=>$pembimbing1->dsn_nip,
            'nama_pembimbing1'=>$pembimbing1->dsn_nama,
            'nip_pembimbing2'=>null,
            'nama_pembimbing2'=>null,
            'tanggal_persetujuan'=>$this->tgl_indo( $pembimbing1->tanggal_persetujuan),
            'tanggal_kadaluarsa'=>$this->tgl_indo( $pembimbing1->tanggal_kadaluarsa),
            'status'=>$this->getStatus($pembimbing1->tanggal_kadaluarsa),
            'jumlah_bimbigan1'=>0,
            'jumlah_bimbigan2'=>0,
            
          ];


         
          if($pembimbing2){
            $response->nip_pembimbing2=$pembimbing2->dsn_nip;
            $response->nama_pembimbing2=$pembimbing2->dsn_nama;
          }
          $bimbinganDatas=Bimbingan::where("bimbingan_mhs_nim","=",$response->mhs_nim)->
          where("bimbingan_status","=","disetujui")->get();

          $jumlah_bimbigan1=0;
          $jumlah_bimbigan2=0;

          foreach ($bimbinganDatas as $value) {
            if($value->bimbingan_dsn_nip=$response->nip_pembimbing1){
                $jumlah_bimbigan1++;
            }else if($value->bimbingan_dsn_nip=$response->nip_pembimbing2){
                $jumlah_bimbigan2++;
            }
          }
          
          $response->jumlah_bimbigan1=$jumlah_bimbigan1;
          $response->jumlah_bimbigan2=$jumlah_bimbigan2;
 
       
            return ResponseFormatter::success(
               $response,
               "Data Successfully Retrived"
           );
    }


    public function downloadSK($id)
    {
        $dosen=Auth::user()->dosen;
        $rawData=SK::join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->join('pelaksana_sidang','pelaksana_sidang.sk_id',"=","sk.id")
        ->join('dosen','dosen.dsn_nip',"=","pelaksana_sidang.pelaksana_dsn_nip")
        ->select("sk.id as sk_id","mahasiswa.mhs_nama","sk.judul_indonesia","sk.judul_inggris",
        "pelaksana_sidang.status as status_pembimbing","dosen.dsn_nama",
        "mahasiswa.mhs_nim",
        "dosen.dsn_nip","tanggal_persetujuan",
        "tanggal_kadaluarsa","nomor_sk")->where('sk.id','=',$id)
        ->orderBy("mhs_nim")->get()->groupBy('status_pembimbing');
      
        if(count($rawData)==0){
         return ResponseFormatter::success(
            null,
            "Data Successfully Retrived"
        );
        }
        $pembimbing1=null;
        if(count($rawData["PEMBIMBING1"])>0){
            $pembimbing1=$rawData["PEMBIMBING1"][0];
         }
 
        $pembimbing2=null;
        if(count($rawData["PEMBIMBING2"])>0){
           $pembimbing2=$rawData["PEMBIMBING2"][0];
        }


        $response = (object) [
            'sk_id' => $pembimbing1->sk_id,
            'nomor_sk'=> $pembimbing1->nomor_sk,
            'judul_indonesia' =>str_replace("\n","",$pembimbing1->judul_indonesia) ,
            'judul_inggris' =>str_replace("\n","", $pembimbing1->judul_inggris) ,
            'mhs_nim'=>$pembimbing1->mhs_nim,
            'mhs_nama'=>$pembimbing1->mhs_nama,
            'nip_pembimbing1'=>$pembimbing1->dsn_nip,
            'nama_pembimbing1'=>$pembimbing1->dsn_nama,
            'nip_pembimbing2'=>null,
            'nama_pembimbing2'=>null,
            'tanggal_persetujuan'=>$this->tgl_indo( $pembimbing1->tanggal_persetujuan),
            'tanggal_kadaluarsa'=>$this->tgl_indo( $pembimbing1->tanggal_kadaluarsa),
            'status'=>$this->getStatus($pembimbing1->tanggal_kadaluarsa),
            'jumlah_bimbigan1'=>0,
            'jumlah_bimbigan2'=>0,
            
          ];


         
          if($pembimbing2){
            $response->nip_pembimbing2=$pembimbing2->dsn_nip;
            $response->nama_pembimbing2=$pembimbing2->dsn_nama;
          }
          $bimbinganDatas=Bimbingan::where("bimbingan_mhs_nim","=",$response->mhs_nim)->get();

          $jumlah_bimbigan1=0;
          $jumlah_bimbigan2=0;

          foreach ($bimbinganDatas as $value) {
            if($value->bimbingan_dsn_nip=$response->nip_pembimbing1){
                $jumlah_bimbigan1++;
            }else if($value->bimbingan_dsn_nip=$response->nip_pembimbing2){
                $jumlah_bimbigan2++;
            }
          }
          
          $response->jumlah_bimbigan1=$jumlah_bimbigan1;
          $response->jumlah_bimbigan2=$jumlah_bimbigan2;
          
 
          view()->share('data',$response);
          $pdf =  PDF::loadView('pdf-sk', ["data"=>$response]);

      return $pdf->download('pdf_file.pdf');
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
    }

    private function getStatus($tanggal_kadarluarsa){
        $today=date("Y-m-d");
        if ($tanggal_kadarluarsa > $today)
        return "AKTIF";
        else
        return "N0N_AKTIF";

    }
}
