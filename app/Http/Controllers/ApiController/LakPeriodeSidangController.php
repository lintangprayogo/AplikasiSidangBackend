<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\PeriodeSidang;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\Sidang;
use App\Models\NilaiSidang;
use App\Models\PelaksanaSidang;
use App\Exports\BeritaAcaraExport;
use Maatwebsite\Excel\Facades\Excel;

class LakPeriodeSidangController extends Controller
{
    
    public function index()
    {
        $rawData=PeriodeSidang::get();
        $response=[];
        foreach($rawData as $data){
            $object = (object) [
                "id"=>$data->id,
                "periode_judul"=>$data->periode_judul,
                "periode_mulai"=>$data->periode_mulai,
                "periode_akhir"=>$data->periode_akhir,
                "jalur_sidang"=>$data->jalur_sidang,
                "status"=>$data->status,
              ];
            
           array_push($response,$object);
        }
        return ResponseFormatter::success(
            $response,
            "Data Successfully Retrived"
        );



    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
           
                'periode_akhir'=>'required',
                'periode_mulai'=>"required",
                "jalur_sidang"=>"required",
            ]);
    
         $periode_sidang=PeriodeSidang::create($request->all());
         $periode_sidang->status="AKTIF";
         return ResponseFormatter::success(
            $periode_sidang,
            "Data Successfully Inserted"
        );
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

    
    public function show($id)
    {
        //
    }

   
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
        try{     
         $periode_sidang=PeriodeSidang::find($id);
         $periode_sidang->update($request->all());
         return ResponseFormatter::success(
            $periode_sidang,
            "Data Successfully Updated"
        );
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $periode_sidang=PeriodeSidang::find($id);
        $periode_sidang->delete();
        return ResponseFormatter::success(
            $periode_sidang,
            "Data Successfully Retrived"
        );
    }

    public function beritaAcaraSidangExcel(Request $request){
        return Excel::download(new BeritaAcaraExport($request->tanggal_mulai,$request->tanggal_akhir), 'berita_acara.xlsx');
    }
}
