<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyPendingUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PendingRegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:pending_users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $token = Str::random(64);

        PendingUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verification_token' => $token,
        ]);

        Mail::to($request->email)->send(new VerifyPendingUser($token));

        return redirect()->back()->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.');
    }

    public function verify(Request $request)
    {
        $pending = PendingUser::where('verification_token', $request->token)->firstOrFail();

        User::create([
            'name' => $pending->name,
            'email' => $pending->email,
            'password' => $pending->password,
            'email_verify_at' => now(),
        ]);

        $pending->delete();

        return redirect('/login')->with('success', 'Xác thực thành công! Bạn có thể đăng nhập.');
    }
}
