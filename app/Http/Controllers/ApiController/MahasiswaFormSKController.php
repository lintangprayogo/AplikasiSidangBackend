<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\FormSK;
use App\Models\SK;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MahasiswaFormSKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswa=Auth::user()->mahasiswa;
        $response = FormSK::where("form_sk_mhs_nim","=",$mahasiswa->mhs_nim)->get();
        return ResponseFormatter::success(
            $response,
            "Data Successfully Retrived"
        );

    }

    public function extendSK(Request $request){
        try{

       $request->validate([
            'judul_indonesia'=>'required',
            'judul_inggris'=>'required',
            'alasan'=>"required",
            "sk_dsn_nama_1"=>"required",
            'sk_nip_1'=>"required"
        ]);
        $mahasiswa=Auth::user()->mahasiswa;
        $sk=SK::where("sk_mhs_nim","=",$mahasiswa->mhs_nim)->first();
        if(!$sk){
            return ResponseFormatter::error(
                [
                    'message' => "You Dont Have Valid SK",
                    'error' => null
                ],
                "You Dont Have Valid SK",
                "422",
            );
        }
        $pdf_name = $request->file('file')->getClientOriginalName();
        $filename = pathinfo($pdf_name,PATHINFO_FILENAME);
        $pdf_ext = $request->file('file')->getClientOriginalExtension();
        $fileNameToStore = $filename.'-'.time().'.'.$pdf_ext;
        $request->file('file')->storeAs('sk/mahasiswa',$fileNameToStore);
        $persetujuan_pembimbing_2=null;
        if($request->sk_nip_2){
            $persetujuan_pembimbing_2="PENDING";
        }
        $formSK=FormSK::create([
                     "judul_indonesia"=>$request->judul_indonesia,
                     "judul_inggris"=>$request->judul_inggris,
                     "alasan"=>$request->alasan,
                     "form_sk_mhs_nim"=>$mahasiswa->mhs_nim,
                     "form_sk_mhs_nama"=>$mahasiswa->mhs_nama,
                     "sk_ta_lama"=>$fileNameToStore,
                     "form_sk_nip_2"=>$request->sk_nip_2,
                     "form_sk_nip_1"=>$request->sk_nip_1,
                     "form_sk_dsn_nama_1"=>$request->sk_dsn_nama_1,
                     "form_sk_dsn_nama_2"=>$request->sk_dsn_nama_2,
                     'persetujuan_pembimbing_1'=>"PENDING",
                     'persetujuan_pembimbing_2'=> $persetujuan_pembimbing_2,
                     'persetujuan_pembimbing_new_1'=>null,
                     'persetujuan_pembimbing_new_2'=>null,
                     'persetujuan_prodi'=>"PENDING",
                     "jenis"=>"PERPANJANG"
                     ]);
        return ResponseFormatter::success($formSK,"Data Successfully Inserted");
        }catch (ValidationException $exception) {
            return ResponseFormatter::error(
                [
                    'message' =>$exception->getMessage(),
                    'error' => $exception
                ],
                $exception->getMessage(),
                $exception->status,
            );
         }
    }



    public function changeSK(Request $request){
        try{

       $request->validate([
            'judul_indonesia'=>'required',
            'judul_inggris'=>'required',
            'judul_indonesia_new'=>'required',
            'judul_inggris_new'=>'required',
            'alasan'=>"required",
            "sk_dsn_nama_1"=>"required",
            'sk_nip_1'=>"required"
        ]);
        $mahasiswa=Auth::user()->mahasiswa;
        $sk=SK::where("sk_mhs_nim","=",$mahasiswa->mhs_nim)->first();
        if(!$sk){
            return ResponseFormatter::error(
                [
                    'message' => "You Dont Have Valid SK",
                    'error' => null
                ],
                "You Dont Have Valid SK",
                "422",
            );
        }
        $pdf_name = $request->file('file')->getClientOriginalName();
        $filename = pathinfo($pdf_name,PATHINFO_FILENAME);
        $pdf_ext = $request->file('file')->getClientOriginalExtension();
        $fileNameToStore = $filename.'-'.time().'.'.$pdf_ext;
        $request->file('file')->storeAs('sk/mahasiswa',$fileNameToStore);
        $persetujuan_pembimbing_2=null;
        $persetujuan_pembimbing_new_1=null;
        $persetujuan_pembimbing_new_2=null;
        if($request->sk_nip_2){
            $persetujuan_pembimbing_2="PENDING";
        }
        if($request->sk_nip_new_1){
            $persetujuan_pembimbing_new_1="PENDING";
        }
        if($request->sk_nip_new_2){
            $persetujuan_pembimbing_new_2="PENDING";
        }
        $formSK=FormSK::create([
                     "judul_indonesia"=>$request->judul_indonesia,
                     "judul_inggris"=>$request->judul_inggris,
                     "judul_indonesia_new"=>$request->judul_indonesia_new,
                     "judul_inggris_new"=>$request->judul_inggris_new,
                     "alasan"=>$request->alasan,
                     "form_sk_mhs_nim"=>$mahasiswa->mhs_nim,
                     "form_sk_mhs_nama"=>$mahasiswa->mhs_nama,
                     "sk_ta_lama"=>$fileNameToStore,
                     "form_sk_nip_2"=>$request->sk_nip_2,
                     "form_sk_nip_1"=>$request->sk_nip_1,
                     "form_sk_nip_new_1"=>$request->sk_nip_new_1,
                     "form_sk_nip_new_2"=>$request->sk_nip_new_2,
                     "form_sk_dsn_nama_1"=>$request->sk_dsn_nama_1,
                     "form_sk_dsn_nama_2"=>$request->sk_dsn_nama_2,
                     'persetujuan_pembimbing_1'=>"PENDING",
                     'persetujuan_pembimbing_2'=> $persetujuan_pembimbing_2,
                     'persetujuan_pembimbing_new_1'=>$persetujuan_pembimbing_new_1,
                     'persetujuan_pembimbing_new_2'=>$persetujuan_pembimbing_new_2,
                     'persetujuan_prodi'=>"PENDING",
                     "jenis"=>"RUBAH_SK"
                     ]);
        return ResponseFormatter::success($formSK,"Data Successfully Inserted");
        }catch (ValidationException $exception) {
            return ResponseFormatter::error(
                [
                    'message' =>$exception->getMessage(),
                    'error' => $exception
                ],
                $exception->getMessage(),
                $exception->status,
            );
         }
    }


    
  
}
