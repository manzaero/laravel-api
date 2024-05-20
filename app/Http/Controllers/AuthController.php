<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|string'
        ]);
        if ($validator->fails()){
            return response() -> json($validator->errors(), 400);
        }
        $user = User::create([
           'email' => $request->email,
           'password' => Hash::make($request->password),
           'gender' => $request->gender,
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'),201);
    }

    public function profile(){
        $user = auth()->user();
        return response()->json(compact('user'));
    }
}
