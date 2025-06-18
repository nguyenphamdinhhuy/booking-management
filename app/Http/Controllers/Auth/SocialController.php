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

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt('password'),
                    'google_id' => $googleUser->getId(),
                ]
            );

            Auth::login($user);
            return redirect('/');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['login' => 'Lỗi đăng nhập Google']);
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
