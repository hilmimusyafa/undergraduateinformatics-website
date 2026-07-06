<?php

namespace App\Http\Controllers;

use App\Models\PasswordRecovery;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiEditPasswordRecoveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if($user) {
            $password_recovery = PasswordRecovery::where('id', $user->id)->get();
            $password_recovery = $password_recovery->first();
            return response()->json([
                'success' => true,
                'first_question' => $password_recovery->first_question,
                'second_question' => $password_recovery->second_question,
                'first_answer' => $password_recovery->first_answer,
                'second_answer' => $password_recovery->second_answer
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'first_question' => 'required',
            'second_question' => 'required',
            'first_answer' => 'required',
            'second_answer' => 'required'
        ]);
        if ($user) {
            $user->password_recovery->first_question = $validatedData['first_question'];
            $user->password_recovery->second_question = $validatedData['second_question'];
            $user->password_recovery->first_answer = strtolower($validatedData['first_answer']);
            $user->password_recovery->second_answer = strtolower($validatedData['second_answer']);
            $user->password_recovery->save();
            return response()->json([
                'success' => true,
                'message' => 'Password recovery updated'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PasswordRecovery $passwordRecovery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PasswordRecovery $passwordRecovery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PasswordRecovery $passwordRecovery)
    {
        //
    }
}
