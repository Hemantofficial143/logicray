<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function attemptLogin(LoginRequest $request){
        if(!Auth::attempt($request->only('email','password'))){
            return redirect()->back()->withErrors(['Invalid Email or Password']);
        }
        return redirect()->route('dashboard');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login.index');
    }


}
