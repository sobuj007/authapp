<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\RegistraionCode;
use App\Mail\RegistrationVerificationSuccess;
use App\Models\PasswordResetCode;
use App\Models\RegstrationVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //

    function gettest()
    {
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


    function verifyUser(Request $request)
    {
        $request->validate([
            'email' => 'required | string | max:256 | exists:users,email',
            'code' => 'required | string | max:6'
        ]);


        try {
            $validCode = RegstrationVerificationCode::where('email', $request->email)
                ->where('code', $request->code)
                ->first();
            if ($validCode->updated_at->diffInMinutes(now()) > env('VERIFICATION_CODE_EXPIRE_TIME', 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verification code has expired'
                ]);
            } else {
                $user = User::where('email', $request->email)->first();
                $user->update([
                    'email_verified_at' => now()
                ]);

                if (Mail::send(new RegistrationVerificationSuccess($user))) {
                    $validCode->delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'Email verified successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to send registration verification success email'
                    ]);
                }
            }


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
    /**
     * Login user and create token
     */

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required | string | max:256 | exists:users,email',
            'password' => 'required | string | min:6'
        ]);

        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return  response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ]);
            } else {
                $user = User::where('email', $request->email)->first();
                $token = $user->createToken('authapp')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'data' => [
                        'info' => new UserResource($user),
                        'token' => $token
                    ]

                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $th->getMessage()
            ]);
        }
    }

    /***
     *  user edit .........
     */

    function edit(Request $request)
    {
        $request->validate([
            'name' => 'required | string | max:256',

            'image' => 'nullable | image'

        ]);

        try {
            /**
             * @var User $user
             */

            $user = Auth::user();

            $filename = $user->image;

            if ($request->file('image')) {

                Storage::delete($filename);


                $filename = time() . '--' . str_replace(' ', '-', $request->image->getClientOriginalName());


                Storage::putFileAs('/', $request->file('image'), $filename);
            }

            if ($user) {
                $user->update([
                    'name' => $request->name,
                    'image' => $filename,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => new UserResource($user)
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error' => $th->getMessage()
            ]);
        }
    }

    /*****
     * User logout and token delete
     */

    function logout(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }
            /****
             * @var User $user
             */
            $user =  Auth::user();
            if ($user->currentAccessToken()->delete()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logout successful'
                ]);
            }

            //code...
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $th->getMessage()
            ]);
        }
    }

    function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required | string | max:256',
            'new_password' => 'required | string | min:6 | confirmed',
            'new_password_confirmation' => 'required | string | min:6'

        ]);

        try {
            /****
             * @var User $user
             */
            $user = Auth::user();
            if ($user) {
                if (password_verify($request->current_password, $user->password)) {
                    $user->update(['password' => bcrypt($request->new_password)]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Password changed successfully'
                    ]);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Password change failed',
                'error' => $th->getMessage()
            ]);
        }
    }


    function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required | string |max:256'
        ]);

        try {
            //code...
            $code = rand(000000, 999999);
            $user = User::where('email', $request->email)->first();
            if (Mail::send(new RegistraionCode($user, $code))) {
                PasswordResetCode::updateOrCreate([
                    'email' => $user->email

                ], [
                    'code' => $code
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Code sended to your mail!'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'User not found!'
            ]);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'success' => true,
                'message' => $th->getMessage()
            ]);
        }
    }

    /***
     * Password reset
     */

    function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required | string | max:256',
            'code' => 'required | string | min:6',
            'new_password' => 'required | string| min:6 | confirmed',
            'new_password_confirmation' => 'required | string | min: 6'
        ]);

        try {
            //code...
            $validCode = PasswordResetCode::where('email', $request->email)->where('code', $request->code)->first();

            if ($validCode->updated_at->diffInMinutes(now()) > env('VERIFICATION_CODE_EXPIRE_TIME', 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verification code has expired'
                ]);
            } else {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    $user->update([
                        'password' => bcrypt($request->new_password)
                    ]);

                    if (Mail::send(new RegistrationVerificationSuccess($user))) {
                        $validCode->delete();
                        return response()->json([
                            'success' => true,
                            'message' => 'Password reset successfully'
                        ]);
                    }
                }
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed',
                'error' => $th->getMessage()
            ]);
        }
    }
}
