<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SocialController extends Controller
{
    // Redirect user tới Google để đăng nhập
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Xử lý callback sau khi người dùng đăng nhập Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Tìm user theo email, nếu chưa có thì tạo mới
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    // có thể thêm password mặc định nếu muốn
                    'password' => bcrypt('defaultpassword')
                ]
            );

            // Đăng nhập user
            Auth::login($user);

            // Chuyển hướng sau đăng nhập
            return redirect()->intended('/dashboard'); // hoặc trang chính của bạn
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại!');
        }
    }
    public function redirectToZalo()
    {
        return Socialite::driver('zalo')->redirect();
    }

    public function handleZaloCallback()
    {
        $user = Socialite::driver('zalo')->stateless()->user();
        dd($user); // Kiểm tra thông tin user
    }

}
