<?php

namespace App\Http\Controllers\Login;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function authProviderRedirect($provider)
    {
        if ($provider) {
            return Socialite::driver($provider)->redirect();
        }
        abort(404);
    }

    public function socialAuthentication($provider)
    {
        try {
            if ($provider) {
                $socialUser = Socialite::driver($provider)->user();

                // Kiểm tra người dùng đã tồn tại
                $user = User::where('email', $socialUser->email)->orWhere('auth_provider_id', $socialUser->id)->first();

                if ($user) {
                    // Đăng nhập người dùng
                    Auth::login($user);
                    return redirect($user->isAdmin() ? 'dashboard' : '/');
                } else {
                    // Kiểm tra trường hợp email không có
                    if (empty($socialUser->email)) {
                        return back()->with('error', 'Email không được cung cấp. Vui lòng đăng ký Email.');
                    }

                    // Tạo người dùng mới
                    $dataUser = User::create([
                        'name' => $socialUser->name,
                        'email' => $socialUser->email,
                        'password' => Hash::make(Str::random(20)), // Mật khẩu ngẫu nhiên
                        'auth_provider_id' => $socialUser->id,
                        'auth_provider' => $provider,
                    ]);

                    Auth::login($dataUser);
                    return redirect('/');
                }
            }

            abort(404);
        } catch (\Throwable $th) {
            Log::error('Login error: ' . $th->getMessage()); // Sử dụng Log::error
            return back()->with('error', 'Đăng nhập không thành công, vui lòng thử lại!');
        }
    }
}
