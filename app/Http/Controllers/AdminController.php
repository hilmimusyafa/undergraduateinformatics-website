<?php

namespace App\Http\Controllers;

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

        // Redirect to admin post index view if credentials are valid
        $request->session()->flash('success', 'Berhasil log in!');
        return redirect()->to(route('posts.index'));
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
        if($user) {
            $first_question = $user->password_recovery->first_question;
            $second_question = $user->password_recovery->second_question;
            return view('PertanyaanLupaPassword', [
                'first_question' => $first_question,
                'second_question' => $second_question,
                'user_id' => $user->id
            ]);
        } else {
            return redirect()->to(route('forgotPassword'));
        }
    }

    /**
     * Display a change password form
     */
    public function passwordRecoveryForm(){
        $user = session('user');
        if($user) {
            return view('GantiPasswordPage', [
                'user_id' => $user->id
            ]);
        } else {
            return redirect()->to(route('forgotPassword'));
        }
        
    }

    /**
     * Handles POST email for forgot password request
     */
    public function submitEmailRecovery(Request $request) 
    {
        $user = User::all()->where('email', $request->email)->first();
        if ($user) {
            return redirect()->to(route('questionForm'))->with('user', $user);
        } else {
            request()->session()->flash('error', 'User tidak ditemukan!');
            return redirect()->to(route('forgotPassword'));
        }
    }
    
    /**
     * Handles POST question answers for forgot password request
     */
    public function submitAnswerRecovery(Request $request) 
    {
        $user = User::all()->where('id', $request->user_id)->first();
        if ($user) {
            $first_answer = strtolower($user->password_recovery->first_answer); // strtolower() is used to make the answer case insensitive (e.g. "A" and "a" are the same
            $second_answer = strtolower($user->password_recovery->second_answer);
            if ($first_answer == $request->first_answer && $second_answer == $request->second_answer) {
                return redirect()->to(route('passwordRecoveryForm'))->with('user', $user);
            } else {
                request()->session()->flash('error', 'Jawaban tidak sesuai!');
                return redirect()->to(route('questionForm'))->with('user', $user);
            }
        } else {
            request()->session()->flash('error', 'Sesi telah kadaluarsa!');
            return redirect()->to(route('forgotPassword'));
        }
    }

    /**
     * Handles POST for change password request
     */
    public function submitPasswordRecovery(Request $request) 
    {
        $user = User::all()->where('id', $request->user_id)->first();
        if ($user) {
            if ($request->new_password == $request->confirm_new_password) {
                $user->password = bcrypt($request->new_password);
                $user->save();
                request()->session()->flash('success', 'Password berhasil diganti!');
                return redirect()->to(route('login'));
            }
            request()->session()->flash('error', 'Password tidak sesuai!');
            return redirect()->to(route('passwordRecoveryForm'))->with('user', $user);
        } else {
            request()->session()->flash('error', 'Sesi telah kadaluarsa!');
            return redirect()->to(route('forgotPassword'));
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
            return redirect()->to(route('login'));
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
            return redirect()->to(route('editPasswordRecoveryQuestion'));
        } else {
            request()->session()->flash('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(route('login'));
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
