<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Imports\SKImport;
use App\Models\SK;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rawData=SK::join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->join('pelaksana_sidang','pelaksana_sidang.sk_id',"=","sk.id")
        ->join('dosen','dosen.dsn_nip',"=","pelaksana_sidang.pelaksana_dsn_nip")
        ->select("sk.id as sk_id","mahasiswa.mhs_nama","sk.judul_indonesia","sk.judul_inggris",
        "pelaksana_sidang.status as status_pembimbing","dosen.dsn_nama",
        "mahasiswa.mhs_nim",
        "dosen.dsn_nip","tanggal_persetujuan",
        "tanggal_kadaluarsa","nomor_sk")
        ->orderBy("mhs_nim")->get()->groupBy('status_pembimbing');

 
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
        $this->validate($request, [
            'excel_file' => 'required|max:50000|mimes:xlsx,xls',
            'tanggal_persetujuan'=>'required'
        ]);

        $excel_file=$request->excel_file;
        $tanggal_persetujuan=$request->tanggal_persetujuan;
        $nomor_sk="232/AKD9/IF-DEK/2021";

        $sk_import=new SKImport($tanggal_persetujuan,$nomor_sk);
      
        Excel::import($sk_import, $excel_file);

        return ResponseFormatter::success(
            $sk_import->response(),
            "Data Successfully Retrived"
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rawData=SK::join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->join('pelaksana_sidang','pelaksana_sidang.sk_id',"=","sk.id")
        ->join('dosen','dosen.dsn_nip',"=","pelaksana_sidang.pelaksana_dsn_nip")
        ->select("sk.id as sk_id","mahasiswa.mhs_nama","sk.judul_indonesia","sk.judul_inggris",
        "pelaksana_sidang.status as status_pembimbing","dosen.dsn_nama",
        "mahasiswa.mhs_nim",
        "dosen.dsn_nip","tanggal_persetujuan",
        "tanggal_kadaluarsa","nomor_sk")->where('sk_id','=',$id)
        ->orderBy("mhs_nim")->get()->groupBy('status_pembimbing');
      
        $pembimbing1=null;
        if(count($rawData["PEMBIMBING1"])>0){
            $pembimbing1=$rawData["PEMBIMBING1"][0];
         }
 
        $pembimbing2=null;
        if (property_exists("PEMBIMBING2",$rawData)){
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
            'tanggal_kadaluarsa'=>$this->tgl_indo( $pembimbing1->tanggal_kadaluarsa)
          ];


         
          if($pembimbing2){
            $response->nip_pembimbing2=$pembimbing2->dsn_nip;
            $response->nama_pembimbing2=$pembimbing2->dsn_nama;
          }

          
 
          view()->share('data',$response);
          $pdf =  PDF::loadView('excel-sk', ["data"=>$response]);

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


    public function showMahasiswa()
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
        if (property_exists("PEMBIMBING2",$rawData)){
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
            'tanggal_kadaluarsa'=>$this->tgl_indo( $pembimbing1->tanggal_kadaluarsa)
          ];


         
          if($pembimbing2){
            $response->nip_pembimbing2=$pembimbing2->dsn_nip;
            $response->nama_pembimbing2=$pembimbing2->dsn_nama;
          }

          
 
       
            return ResponseFormatter::success(
               $response,
               "Data Successfully Retrived"
           );
           
    }
}
