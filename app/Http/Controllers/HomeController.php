<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
    public function index(){
        if(Auth::user()->is_number_verivied	== null || Auth::user()->otp == null){
            if(Auth::user()->is_login_verified == null){
                return redirect()->route('verifyphone');
            } else {
                return view('home.index');
            }
        }
        if(Auth::user()->is_login_verified == null){
            return redirect()->route('verifyphone');
        }
        return view('home.index');
    }
}
