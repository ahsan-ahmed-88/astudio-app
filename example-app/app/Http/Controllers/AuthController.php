<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            
            $request->validate([
            ]);
            $user = User::create([...$request->all(), 'password' => bcrypt($request->password)]);
            return response()->json(['token' => $user->createToken('API Token')->accessToken]);
        
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function login(Request $request) {
        try {
                if (!Auth::attempt($request->only('email', 'password'))) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return response()->json(['token' => Auth::user()->createToken('API Token')->accessToken]);
                
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }
    
}
