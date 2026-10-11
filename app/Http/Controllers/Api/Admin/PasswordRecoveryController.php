<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordRecovery;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;

class PasswordRecoveryController extends Controller
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
        $validatedData = $request->validate([
            'user_id' => 'required',
            'first_answer' => 'required',
            'second_answer' => 'required'
        ]);

        $user = User::find($validatedData['user_id']);
        $password_recovery = $user ? ($user->password_recovery ?: \App\Models\PasswordRecovery::where('user_id', $user->id)->first()) : null;

        if ($user && $password_recovery) {
            if (
                strtolower(trim((string)$validatedData['first_answer'])) === strtolower(trim((string)$password_recovery->first_answer)) &&
                strtolower(trim((string)$validatedData['second_answer'])) === strtolower(trim((string)$password_recovery->second_answer))
            ) {
                $resetToken = \Illuminate\Support\Str::random(40);
                \Illuminate\Support\Facades\Cache::put("api_pwd_reset_{$user->id}", $resetToken, now()->addMinutes(15));

                return response()->json([
                    'response' => 'Jawaban Valid',
                    'reset_token' => $resetToken,
                ]);
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
        $user = User::where('id', $id)->first();
        $password_recovery = $user ? ($user->password_recovery ?: \App\Models\PasswordRecovery::where('user_id', $user->id)->first()) : null;

        if ($user && $password_recovery) {
            $question['first_question'] = $password_recovery->first_question;
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
        $validatedData = $request->validate([
            'reset_token' => 'required|string',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password'
        ]);

        $cachedToken = \Illuminate\Support\Facades\Cache::get("api_pwd_reset_{$id}");
        if (! $cachedToken || ! hash_equals((string)$cachedToken, (string)$validatedData['reset_token'])) {
            return response()->json(['error' => 'Token reset tidak valid atau telah kadaluarsa'], 403);
        }

        $user = User::where('id', $id)->first();
        if ($user) {
            $user->update(['password' => bcrypt($validatedData['new_password'])]);
            \Illuminate\Support\Facades\Cache::forget("api_pwd_reset_{$id}");
            return response()->json(['response' => 'Password berhasil diupdate']);
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
