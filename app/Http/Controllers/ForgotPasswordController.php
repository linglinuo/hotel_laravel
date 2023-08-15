<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function submitForgetPasswordForm(ForgetPasswordRequest $request)
    {
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('mails.resetPassword', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    public function showResetPasswordForm($token, $email)
    {
        return view('redirect')->with('query', 'action=resetPassword&email=' . $email);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8',
        ]);
        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();
        if (!$updatePassword) {
            return view('redirect')->with('query', 'action=resetPassword')->with('error', 'Invalid token!');
        }
        dd('123');
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return view('redirect')->with('message', 'Your password has been changed!');
    }
}
