<?php

namespace App\Http\Controllers\Api;

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

            $request->validate([
                'username' =>'required',
                'password' => 'required'
            ]);

            //Checkin Credentials
            $credentials = request(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error(
                    ['message' =>
                    'Unauthorized'],
                    'Authentication failed',
                    500
                );
            }

            //if user has pass credentials
            $user = User::where('username', $request->username)->first();
        
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception("Invalid Credentials");
            } else {
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                $user['token']=$tokenResult;
                return ResponseFormatter::success([
                    
                    'user' => $user
                ], 'Authenticated');
            }
        
    }
}
