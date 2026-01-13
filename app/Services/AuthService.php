<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class AuthService
{


    // public function login(array $data)
    // {
    //     $remember = isset($data['remember']) ? true : false;
    //     if (Auth::attempt(Arr::except($data, ['remember']), $remember)) {
    //         return Auth::user();
    //     }
    //     return false;
    // }

    public function login(array $data)
    {
        $remember = !empty($data['remember']);

        $credentials = Arr::only($data, ['email', 'password']);

        return Auth::attempt($credentials, $remember);
    }

    public function register(array $data)
    {
        // Đảm bảo slug được tạo nếu không có
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['name']);
        }
        
        // Đặt giá trị mặc định
        $data['type'] = $data['type'] ?? User::TYPE_MEMBER;
        $data['is_active'] = $data['is_active'] ?? true;
        
        $user = User::create($data);
        Auth::login($user);
        return $user;
    }

}
