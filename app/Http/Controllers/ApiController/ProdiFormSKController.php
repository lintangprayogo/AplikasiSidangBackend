<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\FormSK;
use App\Models\PelaksanaSidang;
use App\Models\SK;

class ProdiFormSKController extends Controller
{
    public function index()
    {
   
        $response = FormSK::get();
        return ResponseFormatter::success(
            $response,
            "Data Successfully Retrived"
        );

    }

    public function formSKAccept($id){
        $formSK=FormSK::find($id);
        $sk=SK::where("sk_mhs_nim","=",$formSK->form_sk_mhs_nim)->first();
        if($formSK->jenis=="PERPANJANG" && $sk->extend_count<2 &&
         $formSK->persetujuan_pembimbing_1=="DISETUJUI" 
         &&( $formSK->persetujuan_pembimbing_2=="DISETUJUI"||$formSK->persetujuan_pembimbing_2==null)
        ){
            $this->extendSKDate($sk);
        }else if($formSK->jenis == "RUBAH_SK" && $formSK->persetujuan_pembimbing_1=="DISETUJUI" 
        &&( $formSK->persetujuan_pembimbing_2=="DISETUJUI"||$formSK->persetujuan_pembimbing_2==null) ){
            if($formSK->form_sk_nip_new_1 && $formSK->persetujuan_pembimbing_new_1=="DISETUJUI"&&
            ($formSK->persetujuan_pembimbing_new_2=="DISETUJUI"||$formSK->persetujuan_pembimbing_new_2==null)
        
            ){
                $sk=$this->createSK($formSK);
            }else {
                $sk= $this->changeSKTitle($sk,$formSK->judul_indonesia_new,$formSK->judul_inggris_new);
            }
        }
        $formSK->persetujuan_prodi="DISETUJUI";
        $formSK->save();
        return ResponseFormatter::success(
            $formSK,
            "Data Successfully Accepted"
        );
      
    }

    public function formSKReject($id){
        $formSK=FormSK::find($id);
        $formSK->persetujuan_prodi="DITOLAK";
        $formSK->save();
        return ResponseFormatter::success(
            $formSK,
            "Data Successfully Rejected"
        );
    }

    private function extendSKDate($sk){
        $kadaluarsa="$sk->tanggal_kadaluarsa";
        $extendDate=date('Y-m-d', strtotime('+3 month', strtotime($kadaluarsa)));
        $sk->tanggal_kadaluarsa=$extendDate;
        $sk->save();
        return $sk;
    }

    private function changeSKTitle($sk,$judul_indonesia_new,$judul_inggris_new){
        $sk->judul_indonesia=$judul_indonesia_new;
        $sk->judul_inggris=$judul_inggris_new;
        $sk->save();
        return $sk;
    }

    private function createSK($formSK){
        SK::where('sk_mhs_nim', $formSK->form_sk_mhs_nim)->delete();
        $nomor_sk="232/AKD9/IF-DEK/".date('Y');
        $today="".date('Y-m-d')."";
        $sk= SK::create([
             "nomor_sk"=>$nomor_sk,
             "sk_mhs_nim"=>$formSK->form_sk_mhs_nim,
             "judul_indonesia"=>$formSK->judul_indonesia_new,
             "judul_inggris"=>$formSK->judul_inggris_new,
             "tanggal_persetujuan"=>$today,
             "tanggal_kadaluarsa"=>date('Y-m-d', strtotime('+6 month', strtotime($today)))
             ]);
             if($formSK->form_sk_nip_new_1){
             PelaksanaSidang::create(["sk_id"=>$sk->id,
             "pelaksana_dsn_nip"=>$formSK->form_sk_nip_new_1,
             "status"=>"PEMBIMBING1"]);
             }
             if($formSK->form_sk_nip_new_2){
                 PelaksanaSidang::create(["sk_id"=>$sk->id,
                 "pelaksana_dsn_nip"=>$formSK->form_sk_nip_new_2,
                 "status"=>"PEMBIMBING2"]);
             }
        return $sk;
    }

    
}
