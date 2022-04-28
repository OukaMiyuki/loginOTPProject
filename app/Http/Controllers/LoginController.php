<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\Redirect;
use App\Models\User;
// use Twilio\Rest\Client; //menggunakan twilio

class LoginController extends Controller {

    public function otp(){
        $otp = rand(10000,99999);
        return $otp;
    }

    public function show() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // request()->validate([
        //     'number' => 'required',
        //     'password' => 'required',
        // ]);
        // return $request->all();
        $credentials = $request->only('number', 'password');
        if (Auth::attempt(['number' => $request->number, 'password' => $request->password])) {
            // return "berhasil";
            $user = User::Where('number', $request->number)->first();
            $user->update([
                'is_login_verified' => 1
            ]);
            auth()->login($user);
            if($user->is_number_verivied == null){
                return redirect()->route('verifyphone');
            }
            return redirect()->route('user.home');
        } else {
            return "Login Salah!".'<a href="/login"> Login Page </a>';
        }
        // return "salah";
        return redirect()->route('login.show');
    }

    public function verify(){
        return view('auth.verify');
    }

    public function verifyPhoneNumber(Request $request){
        if (Auth::check()){
            if(Auth::user()->otp == $request->otp){
                $id = Auth::user()->id;
                $user = User::find($id);
                $user->update([
                    'is_number_verivied' => 1,
                    'is_login_verified' => 1
                ]);
            } else{
                return "OTP Salah!";
            }
        }
        return redirect()->route('user.home');
    }

    public function requestNewOTP(){
        $login_otp = $this->otp();
        if (Auth::check()){
            $id = Auth::user()->id;
            $user = User::find($id);
            $otpSend = $this->whatsappNotification($user);
            //hilangkan tanda comment untuk melakukan test
            //$json = $otpSend->getBody()->getContents();
            //$data = json_decode($json);
            // return $data->status //menampilkan result API
            // return $data; //menampilkan result API
            $user->update([
                'otp' => $login_otp
            ]);
        }
        return redirect()->route('user.home');
    }

    public function showLoginOTP(){
        return view('auth.otplogin');
    }

    public function loginNumber(Request $request){
        $login_otp = $this->otp();
        $user = User::where('number', $request->number)->first();
        Auth::login($user);
        if(Auth::check()){
            if($user->is_number_verivied != null){
                $user->update([
                    'otp' => $login_otp
                ]);
                $otpSend = $this->whatsappNotification($user);
                //hilangkan tanda comment untuk melakukan test
                //$json = $otpSend->getBody()->getContents();
                //$data = json_decode($json);
                // return $data->status //menampilkan result API
                // return $data; //menampilkan result API
            }

            return redirect()->route('verifyphone');
        }
    }

    private function whatsappNotification(User $user) {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('http://srv.geogiven.co.id:8099/send-message', [
            'json' => ['number' => $user->number, 'message' => $user->otp] // or 'json' => [...]
        ]);
        return $response;
        //kirim pesan dengan menggunakan twilio
        // $sid    = getenv("TWILIO_AUTH_SID");
        // $token  = getenv("TWILIO_AUTH_TOKEN");
        // $wa_from= getenv("TWILIO_WHATSAPP_FROM");
        // $twilio = new Client($sid, $token);

        // $body = "Harap masukkan kode verifikasi berikut : ".$user->otp;

        // return $twilio->messages->create("whatsapp:$user->number",["from" => "whatsapp:$wa_from", "body" => $body]);
    }

    protected function authenticated(Request $request, $user)  {
        return redirect()->intended();
    }
}
