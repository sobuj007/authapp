<?php

namespace App\Http\Controllers;

use App\Mail\RegistraionCode;
use App\Mail\RegistrationVerificationSuccess;
use App\Models\RegstrationVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //

 function gettest(){
        return response()->json([
            'success' => true,
            'message' => 'Test API endpoint is working'
        ], 200);
 }
    function register(Request $request)
    {

        $request->validate([
            'name' => 'required | string | max:256',
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
                    'message' => 'registered successfully, verifcation mail sent to you mail',

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


    function verifyUser(Request $request){
        $request ->validate([
            'email' => 'required | string | max:256 | exists:users,email',
            'code' => 'required | string | max:6'
        ]);
        $validCode = RegstrationVerificationCode::where('email', $request->email)
        ->where('code', $request->code)
        ->first();

        try {
            if($validCode -> update_at ->diffInMinutes(now())>env('VERIFICATION_CODE_EXPIRE_TIME',10)){
                return response()->json([
                    'success' => false,
                    'message' => 'Verification code has expired'
                ]);
            }
            else{
                $user = User::where('email',$request -> email)->first();
                $user -> update([
                    'email_verified-at' => now()
                ]);
              
                 if(Mail::send(new RegistrationVerificationSuccess($user))){
                      $validCode -> delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'Email verified successfully'
                    ]);
                 }
                 else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to send registration verification success email'
                    ]);
                 }


            }
              return response()->json([
                        'success' => false,
                        'message' => 'Somting went wrong'
                    ]);

            //code...
        } catch (\Throwable $th) {
            //throw $th;
                return response()->json([
                            'success' => false,
                            'message' => 'Somting went wrong',
                            'error' => $th->getMessage()
                        ]);
        }



    }
}
