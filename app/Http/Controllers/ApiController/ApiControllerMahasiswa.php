<?php

namespace App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\mahasiswa;
use App\Models\user;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Models\SK;
use Illuminate\Support\Str;

class ApiControllerMahasiswa extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     function index()
    {
        $response = SK::rightJoin('mahasiswa','sk.sk_mhs_nim',"=","mahasiswa.mhs_nim")
        ->select(
            "mahasiswa.mhs_nama",
        "mahasiswa.mhs_nim",
        "judul_indonesia as judul_nama"
        )
        ->orderBy("mhs_nim")->get();

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
        try{

            $status_user        = "mahasiswa";
            $status_mahasiswa   = "aktif";
            $foto_default       = "default-mahasiswa.jpg";
    
            $this->validate($request, [
                'mhs_nim' => 'required|unique:mahasiswa',
                'mhs_nama' => 'required'
            ]);
    
        
            $user = new user();
            $mahasiswa = new mahasiswa();
            $user->username = $request->mhs_nim;
            $user->password = Hash::make($request->mhs_nim);
            $user->pengguna = $status_user;
    
            $angkatan = '20'. substr($request->mhs_nim, 4,2);
    
            $mahasiswa->mhs_nim     = $request->mhs_nim;
            $mahasiswa->mhs_nama    = $request->mhs_nama;
            $mahasiswa->angkatan    = $angkatan;
            $mahasiswa->mhs_foto    = $foto_default;
            $mahasiswa->status      = $status_mahasiswa;
           

           
                


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
                $mahasiswa->user_id    = $user->id;

                if (!$mahasiswa->save()) {
                    return ResponseFormatter::error(
                        [
                            'message' =>"Something  Error Happen",
                            'error' => []
                        ],
                        "Something  Error Happen",
                        500,
                    );
                }else{
                    $mahasiswa->mhs_nim=$request->mhs_nim;
                    return ResponseFormatter::success(
                        $mahasiswa,
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

    public function show($id)
    {

        
        return "show";

    }

    
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        try{
            $this->validate($request, [
                'mhs_nim' => 'unique:mahasiswa,mhs_nim,'. $id.',mhs_nim',
                'mhs_email' =>'unique:mahasiswa,mhs_email,'. $id.',mhs_nim',
            ]);
            
            $mahasiswa = mahasiswa::find($id);
            if($mahasiswa){
                $mahasiswa->update($request->all());
                return ResponseFormatter::success(
                    $mahasiswa,
                    "Data Successfully Updated"
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
      
       
    
    public function destroy($id)
    {
        
       
        $mahasiswa = mahasiswa::find($id);
        $user = user::find($mahasiswa->user_id);
        if($mahasiswa->delete()){  
            if($user->delete()){
                   return ResponseFormatter::success(
                       $mahasiswa,
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

    public function updateJudulMahasiswa(Request $request, $id) {

        $mahasiswa = mahasiswa::find($id);
        
        $requestJudulId = $request->judul_id;

        if($requestJudulId==0) {
            $mahasiswa->judul_id = null;
        } else {
            $mahasiswa->judul_id = $requestJudulId;
        }

        if (!$mahasiswa->save()) {
            $response = "Sesuatu eror terjadi";
            $showMahasiswa = "Gagal merubah dosen";  
            return response()->json($response, 404); 
        } else {
            $showMahasiswa = "berhasil merubah data mahasiswa";
        }

        $response = [
            'mahasiswa' => $showMahasiswa
        ];

        return response()->json($response, 201);

    }

}


