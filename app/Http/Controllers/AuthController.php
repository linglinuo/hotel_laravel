<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Carbon\Carbon;

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
        return response([
            'message' => 'You have successfully registered & logged in!'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            return response([
                'message' => 'You have successfully logged in!',
                'login_token' =>  $user->createToken($user->email)->plainTextToken
            ], Response::HTTP_OK);
        }
        return response([
            'email' => $request['email'],
            'password' => $request['password']
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        Auth::logout();
        return  response([
            'message' => 'Logged out.'
        ], Response::HTTP_OK);
    }
}
