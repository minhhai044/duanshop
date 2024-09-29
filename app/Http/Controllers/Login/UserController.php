<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRegisterUserRequest;
use App\Http\Requests\PostUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function showform()
    {
        return view('login.index');
    }
    public function login(PostUserRequest $request)
    {
        try {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            if (Auth::attempt($credentials)) {

                request()->session()->regenerate();
                $user = Auth::user();
                /**
                 * @var User $user
                 */
                if ($user->isAdmin()) {
                    return redirect('dashboard');
                }
                return redirect('/');
            }
            return back()->withErrors([
                'email' => 'Email hoặc Password của bạn sai vui lòng kiểm tra lại !!!',
            ])->onlyInput('email');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Lỗi hệ thống !!!');
        }
    }
    public function register(PostRegisterUserRequest $request)
    {
        try {
            $user = User::query()->create($request->all());
            Auth::login($user);
            request()->session()->regenerate();
            return redirect()->route('index');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Lỗi hệ thống !!!');
        }
    }
    public function logout()
    {
        try {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route('index');
        } catch (\Throwable $th) {
            Log::debug(__CLASS__ . '@' . __FUNCTION__, [$th->getMessage()]);
            return back()->with('error', 'Lỗi hệ thống !!!');
        }
    }
}
