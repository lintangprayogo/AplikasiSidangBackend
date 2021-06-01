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
           
            'judul_lama'=>'required',
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
        $formSK=FormSK::create([
                     "judul_lama"=>$request->judul_lama,
                     "alasan"=>$request->alasan,
                     "form_sk_mhs_nim"=>$mahasiswa->mhs_nim,
                     "form_sk_mhs_nama"=>$mahasiswa->mhs_nama,
                     "sk_ta_lama"=>$fileNameToStore,
                     "form_sk_nip_2"=>$request->sk_nip_2,
                     "form_sk_nip_1"=>$request->sk_nip_1,
                     "form_sk_dsn_nama_1"=>$request->sk_dsn_nama_1,
                     "form_sk_dsn_nama_2"=>$request->sk_dsn_nama_2,
                     'persetujuan_pembimbing_1'=>"PENDING",
                     'persetujuan_pembimbing_2'=>"PENDING",
                     'persetujuan_pembimbing_new_1'=>"PENDING",
                     'persetujuan_pembimbing_new_2'=>"PENDING",
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
  
}
