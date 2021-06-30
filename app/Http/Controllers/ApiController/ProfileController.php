<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function updateProfileMahasiswa(Request $request){

       $user= Auth::user();
       $mahasiswa=$user->mahasiswa;
       $photo=$request->photo;
       try{
        if($mahasiswa){
            $mahasiswa->update($request->all());
            return ResponseFormatter::success(
                $mahasiswa,
                "Data Successfully Updated"
            );
         }
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

    public function updatePhotoMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error'=>$validator->errors()], 'Update Photo Fails', 422);
        }

        if ($request->file('file')) {
            $image_name = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($image_name,PATHINFO_FILENAME);
            $image_ext = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename.'-'.time().'.'.$image_ext;
            $request->file('file')->storeAs('mahasiswa',$fileNameToStore);
            $user = Auth::user();
            $mahasiswa=$user->mahasiswa;
            $mahasiswa->mhs_foto = $fileNameToStore;
            $mahasiswa->save();
            return ResponseFormatter::success($mahasiswa,'File successfully uploaded');
        }
    }


    public function updateProfileDosen(Request $request){

        $user= Auth::user();
        $dosen=$user->dosen;
        $photo=$request->photo;
        try{
         if($dosen){
             $dosen->update($request->all());
             return ResponseFormatter::success(
                 $dosen,
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
 
     public function updatePhotoDosen(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'file' => 'required|image|max:2048',
         ]);
 
         if ($validator->fails()) {
             return ResponseFormatter::error(['error'=>$validator->errors()], 'Update Photo Fails', 422);
         }
 
         if ($request->file('file')) {
             $image_name = $request->file('file')->getClientOriginalName();
             $filename = pathinfo($image_name,PATHINFO_FILENAME);
             $image_ext = $request->file('file')->getClientOriginalExtension();
             $fileNameToStore = $filename.'-'.time().'.'.$image_ext;
             $request->file('file')->storeAs('dosen',$fileNameToStore);
             $user = Auth::user();
             $dosen=$user->dosen;
             $dosen->dsn_foto = $fileNameToStore;
             $dosen->save();
             return ResponseFormatter::success($dosen,'File successfully uploaded');
         }
     }
     public function updateProfileProdi(Request $request){

        $user= Auth::user();
        $prodi=$user->prodi;
        $photo=$request->photo;
        try{
         if($prodi){
             $prodi->update($request->all());
             return ResponseFormatter::success(
                 $prodi,
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
 
     public function updatePhotoProdi(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'file' => 'required|image|max:2048',
         ]);
 
         if ($validator->fails()) {
             return ResponseFormatter::error(['error'=>$validator->errors()], 'Update Photo Fails', 422);
         }
 
         if ($request->file('file')) {
             $image_name = $request->file('file')->getClientOriginalName();
             $filename = pathinfo($image_name,PATHINFO_FILENAME);
             $image_ext = $request->file('file')->getClientOriginalExtension();
             $fileNameToStore = $filename.'-'.time().'.'.$image_ext;
             $request->file('file')->storeAs('prodi',$fileNameToStore);
             $user = Auth::user();
             $prodi=$user->prodi;
             $prodi->prd_foto = $fileNameToStore;
             $prodi->save();
             return ResponseFormatter::success($prodi,'File successfully uploaded');
         }
     }

}
