<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use Twilio\Rest\Client; //menggunakan twilio

class RegisterController extends Controller {

    public function otp(){
        $otp = rand(10000,99999);
        return $otp;
    }

    public function show() {
        return view('auth.register');
    }

    public function register(Request $request)  {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'number' => 'required|unique:users',
        //     'password' => 'required|min:8',
        //     'password_confirmation' => 'required|same:password'
        // ]);
        $login_otp = $this->otp();
        $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'is_login_verified' => 1,
            'otp' => $login_otp,
            'password' => $request->password
        ]);
        Log::info("otp = ".$login_otp);
        $otpSend = $this->whatsappNotification($user);
        //hilangkan tanda comment untuk melakukan test
        //$json = $otpSend->getBody()->getContents();
        //$data = json_decode($json);
        // return $data->status //menampilkan result API
        // return $data; //menampilkan result API
        auth()->login($user);
        return redirect()->route('login.show')->with('success', "Account successfully registered.");
    }

    private function whatsappNotification(User $user) {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('http://srv.geogiven.co.id:8099/send-message', [
            'json' => ['number' => $user->number, 'message' => $user->otp] // or 'json' => [...]
        ]);
        return $response;
        // kirim pesan dengan menggunakan twilio
        // $sid    = getenv("TWILIO_AUTH_SID");
        // $token  = getenv("TWILIO_AUTH_TOKEN");
        // $wa_from= getenv("TWILIO_WHATSAPP_FROM");
        // $twilio = new Client($sid, $token);

        // $body = "Harap masukkan kode verifikasi berikut : ".$user->otp;

        // return $twilio->messages->create("whatsapp:$user->number",["from" => "whatsapp:$wa_from", "body" => $body]);
    }
}
