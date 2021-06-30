<?php

namespace App\Http\Controllers\ApiController;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiControllerAuth extends Controller
{
    
    public function allUser(){
        return User::get();
    }
    public function signin(Request $request)
    {

        try {
            $request->validate([
                'username' =>'required',
                'password' => 'required'
            ]);

            //Checkin Credentials
            $credentials = request(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error(
                    ['message' =>
                    'Wrong Username Or Password'],
                    'Wrong Username Or Password',
                    401
                );
            }

            //if user has pass credentials
            $user = User::where('username', $request->username)->first();
        
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception("Invalid Credentials");
            } else {
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                $user['token']="Bearer ".$tokenResult;
                return ResponseFormatter::success([
                    
                    'user' => $user,
                    'dosen'=>$user->dosen,
                    'mahasiswa'=>$user->mahasiswa,
                    'prodi'=>$user->prodi,
                ], 'Authenticated');
            }
        } catch (Exception $exception) {
            return ResponseFormatter::error(
                [
                    'message' =>
                    $exception->getMessage(),
                    'error' => $exception
                ],
                $exception->getMessage(),
                $exception->status
            );
        }


          
        
    }

    public function logout(Request $request)
    {
        $user= $request->user();
        $user->currentAccessToken()->delete();
        return ResponseFormatter::success($user,'Token Revoked');
        
    }

}
