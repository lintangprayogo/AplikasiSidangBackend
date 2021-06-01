<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\FormSK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenFormSKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dosen=Auth::user()->dosen;
        $response = FormSK::where("form_sk_nip_1","=",$dosen->dsn_nip)->
        orWhere("form_sk_nip_2","=",$dosen->mhs_nim)->
        orWhere("form_sk_nip_new_1","=",$dosen->mhs_nim)->
        orWhere("form_sk_nip_new_1","=",$dosen->mhs_nim)
        ->get();
        return ResponseFormatter::success(
            $response,
            "Data Successfully Retrived"
        );

    }

    public function formSKAccept($id){
        $formSK=FormSK::find($id);
        $dosen=Auth::user()->dosen;
        if($formSK->form_sk_nip_1==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_1="DISETUJUI";
        }else if($formSK->form_sk_nip_2==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_2="DISETUJUI";
        }else if($formSK->form_sk_nip_new_1==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_new_1="DISETUJUI";
        }else  if($formSK->form_sk_nip_new_2==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_new_2="DISETUJUI";
        }
        $formSK->save();
        return ResponseFormatter::success(
            $formSK,
            "Data Successfully Accepted"
        );
    }

    public function formSKReject($id){
        $formSK=FormSK::find($id);
        $dosen=Auth::user()->dosen;
        if($formSK->form_sk_nip_1==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_1="DITOLAK";
        }else if($formSK->form_sk_nip_2==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_2="DITOLAK";
        }else if($formSK->form_sk_nip_new_1==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_new_1="DITOLAK";
        }else  if($formSK->form_sk_nip_new_2==$dosen->dsn_nip){
            $formSK->persetujuan_pembimbing_new_2="DITOLAK";
        }
        $formSK->save();
        return ResponseFormatter::success(
            $formSK,
            "Data Successfully Rejected"
        );
    }

  
}
