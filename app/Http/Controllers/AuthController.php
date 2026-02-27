<?php

namespace App\Http\Controllers;

use App\Mail\RegistraionCode;
use App\Models\RegstrationVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //
    function register(Request $request)
    {

        $request->validate([
            'name' => 'requried | string | max:256',
            'email' => 'required | string | max:256 | unique:users',
            'password' => 'required | string | min:6 | confirmed',
            'password_confirmation' => 'required | string | min:6'
        ], [
            'password.confiremed' => 'The password confirmation does not match.'
        ]);

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)

            ]);
            $code = rand(000000, 999999);


            if (Mail::send(new RegistraionCode($user, $code))) {
                RegstrationVerificationCode::create([
                    'email' => $user->email,
                    'code' => $code
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'User registered successfully',

                ], 201);
            }


            return response()->json([
                'success' => false,
                'message' => 'Failed to send registration code email'
            ], 500);



            //code...
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'User registration failed',
                'error' => $th->getMessage()
            ]);
            //throw $th;
        }
    }
}
