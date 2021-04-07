<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\informasi;
use App\Helpers\ResponseFormatter;
use Exception;
class ApiControllerInformasi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $response = informasi::orderby('informasi_id','desc')->get();
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
     try{
        $request->validate([
            'informasi_judul' => 'required',
            'informasi_isi' => 'required',
            'penerbit' => 'required'
        ]);

       

        $judul = $request->informasi_judul;
        $isi = $request->informasi_isi;
        $penerbit = $request->penerbit;

        $informasi = new informasi();
        $informasi->informasi_judul = $judul;
        $informasi->informasi_isi = $isi;
        $informasi->penerbit = $penerbit;
        $informasi->informasi_waktu= date("Y-m-d");
        $informasi->save();
        return ResponseFormatter::success(
            $informasi,
            "Data Successfully Inserted"
        );
         
       
     }catch (Exception $exception) {
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
        $response = informasi::find($id);
        return response()->json($response, 201);
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
    
   
        try{
            $this->validate($request, [
                'informasi_judul' => 'required',
                'informasi_isi' => 'required'
            ]);
    
            $judul = $request->informasi_judul;
            $isi = $request->informasi_isi;
            $informasi = informasi::find($id);
            $informasi->informasi_judul = $judul;
            $informasi->informasi_isi = $isi;
            
            if (!$informasi->save()) {
                $response = [
                    "msg" => "Sesuatu eror terjadi",
                    "success" => false
                ];   
                return response()->json($response, 404);
            } else {
                
                return ResponseFormatter::success(
                    $informasi,
                    "Data Successfully Updated"
                );
            }
             
           
         }catch (Exception $exception) {
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
        $informasi = informasi::find($id);
        
        if (!$informasi->delete()) {
            $response = [
                "msg" => "Sesuatu eror terjadi",
                "success" => false
            ];  
            return response()->json($response, 404);
        } else {
            $response = [
                "msg" => "Berhasil menghapus data",
                "success" => true
            ];  
            return response()->json($response, 201);
        }

    }


}
