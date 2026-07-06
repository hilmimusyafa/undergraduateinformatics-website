<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordRecovery;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;

class ApiPasswordRecoveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::all()->where('email', $request->email)->first();
        if ($user){
            return response()->json([
                'success' => true,
                'user_id' => $user->id
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
        $validatedData = $request->validate([ //validasi input
            'user_id' => 'required',
            'first_answer' =>'required',
            'second_answer' =>'required'
        ]);

        $user = User::where('id', $validatedData['user_id'])->get();
        if (count($user) == 1){
            $password_recovery = PasswordRecovery::where('id', $user->first()->id)->get();
            $password_recovery = $password_recovery->first();
            if (
                ($validatedData['first_answer'] == $password_recovery['first_answer']) and
                ($validatedData['second_answer'] == $password_recovery['second_answer'])
                ) {
                    return response()->json(['response' => 'Jawaban Valid']);
            } else {
                return response()->json(['error' => 'Jawaban tidak sesuai'], 400);
            } 
        } else {
            return response()->json(['error' => 'User tidak valid'], 400);
        }
        

    }

    /**
     * Display the specified resource.
     */


    public function show(String $id)
    {
        $user = User::where('id', $id)->get();
        if (count($user) == 1){
            $password_recovery = PasswordRecovery::where('id', $user->first()->id)->get();
            $password_recovery = $password_recovery->first();
            $question['first_question'] = $password_recovery['first_question'];
            $question['second_question'] = $password_recovery['second_question'];
            return response()->json([
                'question' => $question
            ]);
        } else {
            return response()->json(['error' => 'User tidak valid'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([ //validasi input
            'new_password' =>'required',
            'confirm_new_password' =>'required'
        ]);
        $user = User::where('id', $id)->get();
        if (count($user) == 1){
            if ($validatedData['new_password'] == $validatedData['confirm_new_password']) {
                User::where('id', $id)->update(['password' => bcrypt($validatedData['new_password'])]);
                return response()->json(['response' => 'Password berhasil diupdate']);
            } else {
                return response()->json(['error' => 'Password tidak valid'], 400);
            }
        } else {
            return response()->json(['error' => 'User tidak valid'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
