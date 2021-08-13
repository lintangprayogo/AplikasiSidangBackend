<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\SK;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class TugasAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswa=Auth::user()->mahasiswa;
        if(!$mahasiswa){
            return ResponseFormatter::error(
                [
                    'message' =>"You Are Not A Student",
                    'error' => []
                ],
                "You Are Not A Student",
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
        "tanggal_kadaluarsa","nomor_sk")->where('mhs_nim','=',$mahasiswa->mhs_nim)
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
        if ( isset($rawData["PEMBIMBING2"])){
            if(count($rawData["PEMBIMBING2"])>0){
                $pembimbing2=$rawData["PEMBIMBING2"][0];
             }     
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
            'jumlah_bimbigan1'=>0,
            'jumlah_bimbigan2'=>0,
            'status'=>$this->getStatus($pembimbing1->tanggal_kadaluarsa)
            
          ];


         
          if($pembimbing2){
            $response->nip_pembimbing2=$pembimbing2->dsn_nip;
            $response->nama_pembimbing2=$pembimbing2->dsn_nama;
          }
          $bimbinganDatas=Bimbingan::where("bimbingan_mhs_nim","=",$mahasiswa->mhs_nim)->
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


    public function downloadSK(){
        $mahasiswa=Auth::user()->mahasiswa;
        if(!$mahasiswa){
            return ResponseFormatter::error(
                [
                    'message' =>"You Are Not A Student",
                    'error' => []
                ],
                "You Are Not A Student",
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
        "tanggal_kadaluarsa","nomor_sk")->where('mhs_nim','=',$mahasiswa->mhs_nim)
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
        if ( isset($rawData["PEMBIMBING2"])){
            if(count($rawData["PEMBIMBING2"])>0){
                $pembimbing2=$rawData["PEMBIMBING2"][0];
             }
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
            'jumlah_bimbigan1'=>0,
            'jumlah_bimbigan2'=>0,
            'status'=>$this->getStatus($pembimbing1->tanggal_kadaluarsa)
            
          ];


         
          if($pembimbing2){
            $response->nip_pembimbing2=$pembimbing2->dsn_nip;
            $response->nama_pembimbing2=$pembimbing2->dsn_nama;
          }
          $bimbinganDatas=Bimbingan::where("bimbingan_mhs_nim","=",$mahasiswa->mhs_nim)->get();

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
          $pdf =  PDF::loadView('excel-sk', ["data"=>$response]);

         return $pdf->download('pdf_file.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
