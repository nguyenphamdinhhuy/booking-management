<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // App\Http\Controllers\Auth\SocialController.php

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Tìm hoặc tạo user
        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(uniqid()),
            ]
        );

        // ✅ Kiểm tra trạng thái
        if ($user->status === 'locked') {
            return redirect()->route('locked'); // Bạn cần có route locked và view locked.blade.php
        }

        Auth::login($user);

        // ✅ Điều hướng theo role
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'staff':
                return redirect('/staff/dashboard');
            case 'user':
                return redirect('/');
            default:
                Auth::logout();
                return redirect('/login');
        }
    }


    public function redirectToZalo()
    {
        return Socialite::driver('zalo')->redirect();
    }

    public function handleZaloCallback()
    {
        try {
            $zaloUser = Socialite::driver('zalo')->user();

            $user = User::firstOrCreate(
                ['zalo_id' => $zaloUser->getId()],
                [
                    'name' => $zaloUser->getName(),
                    'email' => $zaloUser->getId() . '@zalo.fake.com',
                    'password' => bcrypt('password'),
                ]
            );

            Auth::login($user);
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['login' => 'Lỗi đăng nhập Zalo']);
        }
    }
}
