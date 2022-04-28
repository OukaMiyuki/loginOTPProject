<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LogoutController extends Controller {
    public function perform() {
        $id = Auth::user()->id;
        $user = User::find($id);
        if(Auth::user()->is_number_verivied != null){
            $user->update([
                'otp' => null
            ]);
        }
        $user->update([
            'is_login_verified' => null
        ]);
        Session::flush();
        Auth::logout();
        return redirect()->route('login.show');
    }
}
