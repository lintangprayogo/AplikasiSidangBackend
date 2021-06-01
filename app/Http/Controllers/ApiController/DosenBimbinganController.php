<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Illuminate\Http\Request;

class DosenBimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $response = Bimbingan::where("bimbingan_dsn_nip","=",$request->dsn_nip)->
        where("bimbingan_mhs_nim","=",$request->mhs_nim)->get();
        return ResponseFormatter::success(
            $response,
            "Data Successfully Inserted"
        );
    }

    public function acceptAll(){
       $effected= Bimbingan::where("bimbingan_status","=","pending")->
        update(["bimbingan_status"=>"disetujui"]);
       
        return ResponseFormatter::success(
                $effected
            ,
            "Data Successfully Updated All"
        );
    }
    public function accept($id){
        $bimbingan= Bimbingan::find($id);
        $bimbingan->bimbingan_status="disetujui";
        $bimbingan->save();
        
         return ResponseFormatter::success(
                $bimbingan
            ,
            "Data Successfully Accepted "
        );
     }

     public function reject($id){
        $bimbingan= Bimbingan::find($id);
        $bimbingan->bimbingan_status="ditolak";
        $bimbingan->save();
        
         return ResponseFormatter::success(
                $bimbingan
            ,
            "Data Successfully Rejected"
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
}
