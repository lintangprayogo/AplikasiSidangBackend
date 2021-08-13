<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Imports\JadwalSidangImport;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\PelaksanaSidang;
use Barryvdh\DomPDF\Facade as PDF;

class SidangProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response=Sidang::join('sk','sk.id',"=","sidang.sk_id")
        ->join('periode_sidang','periode_sidang.id',"=","sidang.periode_id")
        ->join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
    
        ->get();
        return ResponseFormatter::success(
            $response,
            "Data Successfully Retrived"
        );

        
    }


    
    function jurnalDownload($id){
        $sidang=Sidang::find($id);
         return Storage::download('draft_jurnal/mahasiswa/'.$sidang->draft_jurnal);
      }
     
     
      function revisiDownload($id){
       $sidang=Sidang::find($id);
        return Storage::download('lembar_revisi/'.$sidang->revisi);
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
    public function show(Request $request,$id){
        
      
     
        $sidang=Sidang::join('sk','sk.id',"=","sidang.sk_id")
        ->join('periode_sidang','periode_sidang.id',"=","sidang.periode_id")
        ->join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->select("sidang.id as id","persetujuan_pembimbing_1","persetujuan_pembimbing_2","mhs_nama",
        "mhs_nim",
        "draft_jurnal",
        "jalur_sidang",
        "periode_judul",
        "tanggal_sidang",
        "judul_indonesia",
        "jam_berakhir",
        "sk_id",
        "jam_mulai")
        ->where("sidang.id","=",$id)->first();
      
        $pembimbing_1=PelaksanaSidang::where("status","=","PEMBIMBING1")->where("sk_id","=",$sidang->sk_id)
        ->first();
        $pembimbing_2=PelaksanaSidang::where("status","=","PEMBIMBING2")->where("sk_id","=",$sidang->sk_id)
        ->first();
     

       

        $penguji_1=PelaksanaSidang::where("status","=","PENGUJI1")->where("sk_id","=",$sidang->sk_id)
        ->first();
        $penguji_2=PelaksanaSidang::where("status","=","PENGUJI2")->where("sk_id","=",$sidang->sk_id)
        ->first();

    
        $jml_pembimbing=0;
        $jml_penguji=0;


       

        if($pembimbing_1){
            $sidang->pembimbing_1=$pembimbing_1->dosen()->dsn_nama;
            $sidang->pembimbing_nip_1=$pembimbing_1->dosen()->dsn_nip;
            $jml_pembimbing=$jml_pembimbing+1;
        }
        if($pembimbing_2){
            $sidang->pembimbing_2=$pembimbing_2->dosen()->dsn_nama;
            $sidang->pembimbing_nip_2=$pembimbing_2->dosen()->dsn_nip;
            $jml_pembimbing=$jml_pembimbing+1;
        }
        if($penguji_1){
            $sidang->penguji_1=$penguji_1->dosen()->dsn_nama;
            $jml_penguji=$jml_penguji+1;
        }
        if($penguji_2){
            $sidang->penguji_2=$penguji_2->dosen()->dsn_nama;
            $jml_penguji=$jml_penguji+1;
           
        }
     
        if($jml_pembimbing==0){
            if( $request->wantsJson()){
                return ResponseFormatter::success(
                    $sidang,
                    "Data Successfully Accepted"
                );
               }else {
                view()->share('sidang',$sidang);
                $pdf =  PDF::loadView('berita-acara', ["sidang"=>$sidang]);
                return $pdf->download('pdf_file.pdf');
               }
        }

        if($jml_penguji==0){
            if( $request->wantsJson()){
                return ResponseFormatter::success(
                    $sidang,
                    "Data Successfully Accepted"
                );
               }else {
                view()->share('sidang',$sidang);
                $pdf =  PDF::loadView('berita-acara', ["sidang"=>$sidang]);
                return $pdf->download('pdf_file.pdf');
               }
        }

        $nilai_datas=NilaiSidang::where("sidang_id","=",$sidang->id)->get();

        foreach($nilai_datas as $nilai){
            if($nilai->sumber=="PEMBIMBING1"){
                $sidang->nilai_pembimbing1_laporan=$nilai->nilai_laporan;
                $sidang->nilai_pembimbing1_presentasi=$nilai->nilai_presentasi;
                $sidang->nilai_pembimbing1_produk=$nilai->nilai_produk;
            }
            if($nilai->sumber=="PEMBIMBING2"){
                $sidang->nilai_pembimbing2_laporan=$nilai->nilai_laporan;
                $sidang->nilai_pembimbing2_presentasi=$nilai->nilai_presentasi;
                $sidang->nilai_pembimbing2_produk=$nilai->nilai_produk;
            }
            else if($nilai->sumber=="PENGUJI1"){
                $sidang->nilai_penguji1_laporan=$nilai->nilai_laporan;
                $sidang->nilai_penguji1_presentasi=$nilai->nilai_presentasi;
                $sidang->nilai_penguji1_produk=$nilai->nilai_produk;
            }            
            else if($nilai->sumber=="PENGUJI2"){
                $sidang->nilai_penguji2_laporan=$nilai->nilai_laporan;
                $sidang->nilai_penguji2_presentasi=$nilai->nilai_presentasi;
                $sidang->nilai_penguji2_produk=$nilai->nilai_produk;
            }
        }
      
       //pembimbing
        //Laporan
        if($sidang->nilai_pembimbing2_laporan&&$sidang->nilai_pembimbing1_laporan){
            $sidang->ra_laporan=
            ($sidang->nilai_pembimbing1_laporan+$sidang->nilai_pembimbing2_laporan)/$jml_pembimbing;
        }
        else if($sidang->nilai_pembimbing1_laporan){
            $sidang->ra_laporan=$sidang->nilai_pembimbing1_laporan/$jml_pembimbing;
        }
        else if($sidang->nilai_pembimbing2_laporan){
            $sidang->ra_laporan=$sidang->nilai_pembimbing2_laporan/$jml_pembimbing;
        }

        //presentasi
        if($sidang->nilai_pembimbing1_presentasi&&$sidang->nilai_pembimbing2_presentasi){
            $sidang->ra_presentasi=
            ($sidang->nilai_pembimbing1_presentasi+$sidang->nilai_pembimbing2_presentasi)/$jml_pembimbing;
        }
        else if($sidang->nilai_pembimbing1_presentasi){
            $sidang->ra_presentasi=$sidang->nilai_pembimbing1_presentasi/$jml_pembimbing;
        }
        else if($sidang->nilai_pembimbing2_presentasi){
            $sidang->ra_presentasi=$sidang->nilai_pembimbing2_presentasi/$jml_pembimbing ;
        }
      
        //produk
        if($sidang->nilai_pembimbing1_produk&&$sidang->nilai_pembimbing2_produk){
            $sidang->ra_produk=
            ($sidang->nilai_pembimbing1_produk+$sidang->nilai_pembimbing2_produk)/$jml_pembimbing;
        }
        else if($sidang->nilai_pembimbing1_produk){
            $sidang->ra_produk=$sidang->nilai_pembimbing1_produk/$jml_pembimbing;
        }
        else if($sidang->nilai_pembimbing2_produk){
            $sidang->ra_produk=$sidang->nilai_pembimbing2_produk/$jml_pembimbing;
        }
       


        
        //penguji
        if($sidang->nilai_penguji2_laporan&&$sidang->nilai_penguji1_laporan){
            $sidang->rb_laporan=
            ($sidang->nilai_penguji1_laporan+$sidang->nilai_penguji2_laporan)/$jml_penguji;
        }
        else if($sidang->nilai_penguji1_laporan){
            $sidang->rb_laporan=$sidang->nilai_penguji1_laporan/$jml_penguji;
        }
        else if($sidang->nilai_penguji2_laporan){
            $sidang->rb_laporan=$sidang->nilai_penguji2_laporan/$jml_penguji;
        }

        if($sidang->nilai_penguji1_presentasi&&$sidang->nilai_penguji2_presentasi){
            $sidang->rb_presentasi=
            ($sidang->nilai_penguji1_presentasi+$sidang->nilai_penguji2_presentasi)/$jml_penguji;
        }
        else if($sidang->nilai_penguji1_presentasi){
            $sidang->rb_presentasi=$sidang->nilai_penguji1_presentasi/$jml_penguji;
        }
        else if($sidang->nilai_penguji2_presentasi){
            $sidang->rb_presentasi=$sidang->nilai_penguji2_presentasi/$jml_penguji;
        }

        if($sidang->nilai_penguji1_produk&&$sidang->nilai_penguji2_produk){
            $sidang->rb_produk=
            ($sidang->nilai_penguji1_produk+$sidang->nilai_penguji2_produk)/$jml_penguji;
        }
        else if($sidang->nilai_penguji1_produk){
            $sidang->rb_produk=$sidang->nilai_penguji1_produk/$jml_penguji;
        }
        else if($sidang->nilai_penguji2_produk){
            $sidang->rb_produk=$sidang->nilai_penguji2_produk/$jml_penguji;
        }
      
     


        if($sidang->ra_laporan &&$sidang->rb_laporan){
            $sidang->rt_laporan=$sidang->ra_laporan*0.6+$sidang->rb_laporan*0.4;
         }else if($sidang->ra_laporan ){
             $sidang->rt_laporan=$sidang->ra_laporan*0.6;
          }else if($sidang->rb_laporan ){
             $sidang->rt_laporan=$sidang->rb_laporan*0.4;
          }
 
          
        if($sidang->ra_presentasi &&$sidang->rb_presentasi){

            $sidang->rt_presentasi=$sidang->ra_presentasi*0.6+$sidang->rb_presentasi*0.4;
         }else if($sidang->ra_presentasi ){
             $sidang->rt_presentasi=$sidang->ra_presentasi*0.6;
          }else if($sidang->rb_presentasi ){
             $sidang->rt_presentasi=$sidang->rb_presentasi*0.4;
          }
        
          
  
 
          if($sidang->ra_produk &&$sidang->rb_produk){
            $sidang->rt_produk=$sidang->ra_produk*0.6+$sidang->rb_produk*0.4;
         }else if($sidang->ra_produk ){
             $sidang->rt_produk=$sidang->ra_produk*0.6;
          }else if($sidang->rb_produk ){
             $sidang->rt_produk=$sidang->rb_produk*0.4;
          }
         
          $sidang->na_laporan=$sidang->rt_laporan*0.35;
          $sidang->na_presentasi=$sidang->rt_presentasi*0.30;
          $sidang->na_produk=$sidang->rt_produk*0.35;


          $sidang->na_total=$sidang->na_laporan+$sidang->na_presentasi+$sidang->na_produk;
          $sidang->na_total=number_format($sidang->na_total,2);

          $sidang->rt_laporan= number_format($sidang->rt_laporan,2);
          $sidang->rt_presentasi= number_format($sidang->rt_presentasi,2);
          $sidang->rt_produk= number_format($sidang->rt_produk,2);

          $sidang->ra_laporan= number_format($sidang->ra_laporan,2);
          $sidang->ra_presentasi= number_format($sidang->ra_presentasi,2);
          $sidang->ra_produk= number_format($sidang->ra_produk,2);

          $sidang->rb_laporan= number_format($sidang->rb_laporan,2);
          $sidang->rb_presentasi= number_format($sidang->rb_presentasi,2);
          $sidang->rb_produk= number_format($sidang->rb_produk,2);

          $sidang->na_laporan= number_format($sidang->na_laporan,2);
          $sidang->na_presentasi= number_format($sidang->na_presentasi,2);
          $sidang->na_produk= number_format($sidang->na_produk,2);
          if( $sidang->tanggal_sidang)
          $sidang->tanggal_sidang=$this->tgl_indo($sidang->tanggal_sidang);

          $sidang->tanggal_revisi =date('Y-m-d', strtotime('+15 day', strtotime($sidang->tanggal_sidang)));
          $sidang->tanggal_revisi=$this->tgl_indo($sidang->tanggal_revisi);

          $sidang->index_nilai =$this->indexNilai($sidang->na_total);




          

      
       if( $request->wantsJson()){
        return ResponseFormatter::success(
            $sidang,
            "Data Successfully Accepted"
        );
       }else {
        view()->share('sidang',$sidang);
        $pdf =  PDF::loadView('berita-acara', ["sidang"=>$sidang]);
        return $pdf->download('pdf_file.pdf');
       }
      

        
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

    public function plotSidang(Request $request){
        $this->validate($request, ['excel_file' => 'required|max:50000|mimes:xlsx,xls']);

        $excel_file=$request->excel_file;
        $jadwalSidangImport=new JadwalSidangImport();
        Excel::import($jadwalSidangImport, $excel_file);
        return ResponseFormatter::success(
            $jadwalSidangImport->response(),
            "Data Successfully Inserted"
        );
        
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
}
