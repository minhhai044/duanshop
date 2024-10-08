<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRegisterUserRequest;
use App\Http\Requests\PostUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

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
            $remember = $request->has('remember');
            if (Auth::attempt($credentials, $remember)) {

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
    public function formsendmail()
    {
        return view('login.forgot-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Gửi email chứa liên kết đặt lại mật khẩu
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Xử lý việc đặt lại mật khẩu
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Cập nhật mật khẩu người dùng
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
