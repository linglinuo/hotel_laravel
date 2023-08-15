<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Helpers\RSA;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']) //加密
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials); //判定登入資訊是否正確

        $token = Str::random(64);

        UserVerify::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        Mail::send('mails.emailVerificationMail', ['email' => $request['email'], 'token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });

        return response([
            'message' => 'You have successfully registered & logged in!'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $request['password'] = RSA::rsa_decode($request['password']);
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user->is_email_verified) {
                return response([
                    'message' => 'You have successfully logged in!',
                    'login_token' =>  $user->createToken($user->email)->plainTextToken
                ], Response::HTTP_OK);
            } else {
                return response([
                    'message' => 'You need to confirm your account. We have sent you an activation code, please check your email.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        return response([
            'email' => $request['email'],
            'password' => $request['password']
        ], Response::HTTP_BAD_REQUEST);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return  response([
            'message' => 'Logged out.'
        ], Response::HTTP_OK);
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }
        return view('redirect')->with('message', $message)->with('query', 'action=login');
    }
}
