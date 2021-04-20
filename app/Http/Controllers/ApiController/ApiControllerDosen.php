<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\dosen;
use App\Models\user;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Hash;
class ApiControllerDosen extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $response = dosen::all();
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
        $status_user = "dosen";
        $foto_default = "default-dosen.jpg";

    
          $this->validate($request, [
              'dsn_nip' => 'required|unique:dosen',  
              'dsn_nama' => 'required', 
              'dsn_kode' => 'required|unique:dosen',
          ]);

          $user = new user();
          $dosen = new dosen();

          $user->username = $request->dsn_nip;
          $user->password = Hash::make($request->dsn_nip);
          $user->pengguna = $status_user;

          
          if (!$user->save()) {
          return ResponseFormatter::error(
                  [
                      'message' =>"Something  Error Happen",
                      'error' => []
                  ],
                  "Something  Error Happen",
                  500,
              );
          }
          
          $dosen->dsn_nip     = $request->dsn_nip;
          $dosen->dsn_nama    = $request->dsn_nama;
          $dosen->dsn_kode    = $request->dsn_kode;
          $dosen->dsn_foto    = $foto_default; 
          $dosen->user_id   = $user->id;

          if (!$dosen->save()) {
              return ResponseFormatter::error(
                  [
                      'message' =>"Something  Error Happen",
                      'error' => []
                  ],
                  "Something  Error Happen",
                  500,
              );
          } else {
              $dosen->dsn_nip=$request->dsn_nip;
              return ResponseFormatter::success(
                  $dosen,
                  "Data Successfully Added"
              );
  
          }
        }catch (Exception $exception) {
            if(!$exception->status){
                $exception->status=500;
            }
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

        // $response = DB::table('dosen')
        // ->join('user', 'user.username', '=', 'user.username')
        // ->where('dsn_nip', $id)
        // ->get();
        
        $response = dosen::find($id);
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
        
       
        $dosen = dosen::find($id);
        $user = user::find($dosen->user_id);

        $nip = $request->dsn_nip;

        if (!empty($nip)) {
            $user->username = $nip;
            $dosen->dsn_nip = $nip;
            if (!$user->save()) {
                return ResponseFormatter::error(
                    [
                        'message' =>"Something  Error Happen",
                        'error' => []
                    ],
                    "Something  Error Happen",
                    500,
                );
            } 
        }

        $dosen->dsn_nama    = $request->dsn_nama;
        $dosen->dsn_kode    = $request->dsn_kode;
        $dosen->dsn_kontak  = $request->dsn_kontak;
        $dosen->dsn_email   = $request->dsn_email;
        $dosen->batas_bimbingan = $request->batas_bimbingan;
    

        if (!$dosen->save()) {
            return ResponseFormatter::error(
                [
                    'message' =>"Something  Error Happen",
                    'error' => []
                ],
                "Something  Error Happen",
                500,
            );
        } else { 
            return ResponseFormatter::success(
                $dosen,
                "Data Successfully Added"
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
        
      
        $dosen = dosen::find($id);
       
        $user = user::find($dosen->user_id);
        
        if($dosen->delete()){  
         if( $user->delete()){
                return ResponseFormatter::success(
                    $dosen,
                    "Data Successfully Removed"
                );
            }
        }

        return ResponseFormatter::error(
            [
                'message' =>"Failed To Remove Data",
                'error' =>[]
            ],
            "Failed To Remove Data",
            500,
        );
       
       


    }


    public function updatePure(Request $request, $id)
    {
        
        $user = user::find($id);
        $dosen = dosen::find($id);

        $nip = $request->dsn_nip;

        if (!empty($nip)) {
            $user->username = $nip;
            $dosen->dsn_nip = $nip;
            
            if (!$user->save()) {
                $response = "Sesuatu eror terjadi"; 
                $showUser = 'Gagal merubah user';
                return response()->json($response, 404); 
            } else {
                $showUser = "Berhasil merubah data user dosen";
            }
        
        } else {
            $showUser = "tidak merubah user dosen";
        }

        $dosen->dsn_nama    = $request->dsn_nama;
        $dosen->dsn_kode    = $request->dsn_kode;
        $dosen->dsn_kontak  = $request->dsn_kontak;
        $dosen->dsn_email   = $request->dsn_email;


        if (!$dosen->save()) {
            $response = "Sesuatu eror terjadi";
            $showDosen = "Gagal merubah dosen";  
            return response()->json($response, 404); 
        } else {
            $showDosen = "Berhasil merubah data dosen";
        }

        $response = [
            'user'      => $showUser,
            'dosen'     => $showDosen
        ];
        return response()->json($response, 201);

    }

}
