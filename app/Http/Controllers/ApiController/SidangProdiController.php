<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Imports\JadwalSidangImport;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
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
