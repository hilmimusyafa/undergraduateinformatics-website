<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    /**
    * Attempt admin login via API
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
          
            $id = auth()->user()->id;
            return response()->json([
                
                'token' => $user->createToken('user-login')->plainTextToken,
                'user' => $id,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }

    /**
    * Attempt admin logout via API
    */
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ],201);
    }
}
