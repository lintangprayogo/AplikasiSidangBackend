<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\PeriodeSidang;
use App\Models\Sidang;
use App\Models\SK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MahasiswaPendaftaranSidangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mahasiswa=Auth::user()->mahasiswa;

        $response=Sidang::join('sk','sk.id',"=","sidang.sk_id")
        ->join('periode_sidang','periode_sidang.id',"=","sidang.periode_id")
        ->join('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->where("sk_mhs_nim","=",$mahasiswa->mhs_nim)
        ->get();
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


    public function store(Request $request)
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
        try{
            $request->validate([
                 "file"=>"required"
             ]);
             $sk=SK::where("sk_mhs_nim","=",$mahasiswa->mhs_nim)->first();
             $periodeSidang=PeriodeSidang::find($request->periode_id);
             $pdf_name = $request->file('file')->getClientOriginalName();
             $filename = pathinfo($pdf_name,PATHINFO_FILENAME);
             $pdf_ext = $request->file('file')->getClientOriginalExtension();
             $fileNameToStore = $filename.'-'.time().'.'.$pdf_ext;
             $request->file('file')->storeAs('draft_jurnal/mahasiswa',$fileNameToStore);

             $pendaftaran_sidang=Sidang::create(
                [
                    "sk_id"=>$sk->id,
                    "periode_id"=>$periodeSidang->id,
                    "draft_jurnal"=>$fileNameToStore
                ]
             );
             $pendaftaran_sidang->periode_judul=$periodeSidang->periode_judul;
             $pendaftaran_sidang->jalur_sidang=$periodeSidang->jalur_sidang;
             

             return ResponseFormatter::success($pendaftaran_sidang,"Data Successfully Inserted");
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
        
    }

    public function update(Request $request, $id)
    {
        
    }

    
    public function destroy($id)
    {
    
    }
  
}
