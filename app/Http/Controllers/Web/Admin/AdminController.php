<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a login form.
     */
    public function loginForm()
    {
        return view('LoginPage');
    }

    /**
     * Attempt login
     */
    public function login(Request $request) {
        // Return back with error if credentials are invalid
        if (auth()->attempt(request(['email', 'password'])) == false) {
            return back()->withError('Email atau password salah, silahkan coba lagi');
        }

        // Redirect to admin dashboard if credentials are valid
        $request->session()->flash('success', 'Berhasil log in!');
        return redirect()->to(route('admin.dashboard'));
    }

    /**
     * Backend for forgot password
     */

    /**
     * Display a forgot password form
     */
    public function forgotForm() 
    {
        return view('LupaPasswordPage');
    }

    /**
     * Display a forgot password question form
     */
    public function questionForm() 
    {
        $user = session('user');
        $recovery = $user ? ($user->password_recovery ?: \App\Models\PasswordRecovery::where('user_id', $user->id)->first()) : null;
        if($user && $recovery) {
            $first_question = $recovery->first_question;
            $second_question = $recovery->second_question;
            return view('PertanyaanLupaPassword', [
                'first_question' => $first_question,
                'second_question' => $second_question,
                'user_id' => $user->id
            ]);
        } else {
            return redirect()->to(route('admin.forgotPassword'));
        }
    }

    /**
     * Display a change password form
     */
    public function passwordRecoveryForm(){
        $verifiedUserId = session('password_reset_verified_user_id');
        $user = session('user');
        if($user && $verifiedUserId && (int)$verifiedUserId === (int)$user->id) {
            return view('GantiPasswordPage', [
                'user_id' => $user->id
            ]);
        } else {
            return redirect()->to(route('admin.forgotPassword'));
        }
    }

    /**
     * Handles POST email for forgot password request
     */
    public function submitEmailRecovery(Request $request) 
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return redirect()->to(route('admin.questionForm'))->with('user', $user);
        } else {
            request()->session()->flash('error', 'User tidak ditemukan!');
            return redirect()->to(route('admin.forgotPassword'));
        }
    }
    
    /**
     * Handles POST question answers for forgot password request
     */
    public function submitAnswerRecovery(Request $request) 
    {
        $request->validate([
            'user_id' => 'required',
            'first_answer' => 'required',
            'second_answer' => 'required',
        ]);

        $user = User::where('id', $request->user_id)->first();
        $recovery = $user ? ($user->password_recovery ?: \App\Models\PasswordRecovery::where('user_id', $user->id)->first()) : null;

        if ($user && $recovery) {
            $first_answer = strtolower(trim((string)$recovery->first_answer));
            $second_answer = strtolower(trim((string)$recovery->second_answer));
            $inputFirst = strtolower(trim((string)$request->first_answer));
            $inputSecond = strtolower(trim((string)$request->second_answer));

            if ($first_answer === $inputFirst && $second_answer === $inputSecond) {
                session()->put('password_reset_verified_user_id', $user->id);
                return redirect()->to(route('admin.passwordRecoveryForm'))->with('user', $user);
            } else {
                request()->session()->flash('error', 'Jawaban tidak sesuai!');
                return redirect()->to(route('admin.questionForm'))->with('user', $user);
            }
        } else {
            request()->session()->flash('error', 'Sesi telah kadaluarsa!');
            return redirect()->to(route('admin.forgotPassword'));
        }
    }

    /**
     * Handles POST for change password request
     */
    public function submitPasswordRecovery(Request $request) 
    {
        $request->validate([
            'user_id' => 'required',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'confirm_new_password.same' => 'Konfirmasi password tidak sesuai.',
        ]);

        $verifiedUserId = session('password_reset_verified_user_id');
        if (! $verifiedUserId || (int)$verifiedUserId !== (int)$request->user_id) {
            request()->session()->flash('error', 'Sesi verifikasi tidak valid atau telah kadaluarsa!');
            return redirect()->to(route('admin.forgotPassword'));
        }

        $user = User::where('id', $request->user_id)->first();
        if ($user) {
            $user->password = bcrypt($request->new_password);
            $user->save();
            session()->forget('password_reset_verified_user_id');
            request()->session()->flash('success', 'Password berhasil diganti!');
            return redirect()->to(route('admin.login'));
        } else {
            request()->session()->flash('error', 'Sesi telah kadaluarsa!');
            return redirect()->to(route('admin.forgotPassword'));
        }
    }

    /**
     * Display change questions & answers for forgot password
     */
    public function editPasswordRecoveryQuestion() {
        $user = Auth::user();
        if($user) {
            $first_question = $user->password_recovery->first_question;
            $second_question = $user->password_recovery->second_question;
            $first_answer = $user->password_recovery->first_answer;
            $second_answer = $user->password_recovery->second_answer;
            return view('AdminPertanyaan.AdminPageEditPertanyaan', [
                'first_question' => $first_question,
                'second_question' => $second_question,
                'first_answer' => $first_answer,
                'second_answer' => $second_answer
            ]);
        } else {
            request()->session()->flash('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(route('admin.login'));
        }
    }

    /**
     * Handles POST for change question & answers request
     */
    public function updatePasswordRecoveryQuestion(Request $request) 
    {
        $user = Auth::user();
        if ($user) {
            $user->password_recovery->first_question = $request->first_question;
            $user->password_recovery->second_question = $request->second_question;
            $user->password_recovery->first_answer = strtolower($request->first_answer);
            $user->password_recovery->second_answer = strtolower($request->second_answer);
            $user->password_recovery->save();
            request()->session()->flash('success', 'Pertanyaan berhasil diupdate!');
            return redirect()->to(route('admin.editPasswordRecoveryQuestion'));
        } else {
            request()->session()->flash('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(route('admin.login'));
        }
    }

    /**
     * Function for logging out
     */
    public function logout() {
        Session::flush();
        Auth::logout();
        request()->session()->flash('success', 'Berhasil log out!');
        return redirect(route('home'));
    }
}
