<?php

namespace App\Http\Controllers\Api;

use App\Events\OtpGenerated;
use App\Events\PasswordGenerated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use ApiResponseTrait;
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        try {
            $payload = $request->validated() + [
                'remember' => $request->boolean('remember'),
            ];

            $ok = $this->authService->login($payload);

            if ($ok) {
                $user = Auth::user();

                if ($user->is_active == 0) {
                    return $this->errorResponse('Tài khoản của bạn chưa được xác thực.', Response::HTTP_FORBIDDEN);
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                return $this->successResponse([
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 'Đăng nhập thành công!');
            }

            return $this->errorResponse('Thông tin đăng nhập không đúng hoặc tài khoản bị khóa.', Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            Log::error('Lỗi đăng nhập: ' . $th->getMessage());
            return $this->errorResponse('Đăng nhập không thành công. Vui lòng thử lại.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request)
    {

        /**
         * slug : Slug của người dùng
         * otp : Mã OTP mà người dùng nhập vào
         */
        try {

            $user = User::with(['oneTimePassword'])->where('slug', $request->slug)->first();
            if (!$user) {
                return $this->errorResponse('Người dùng không tồn tại.', Response::HTTP_NOT_FOUND);
            }
            if ($user && $user->oneTimePassword) {
                $otpRecord = $user->oneTimePassword;
                if ($otpRecord->otp !== $request->otp) {
                    return $this->errorResponse('Mã OTP không đúng.', Response::HTTP_BAD_REQUEST);
                }
                if ($otpRecord->expires_at < now()) {
                    return $this->errorResponse('Mã OTP đã hết hạn.', Response::HTTP_BAD_REQUEST);
                }
            } else {
                return $this->errorResponse('Mã OTP không hợp lệ.', Response::HTTP_BAD_REQUEST);
            }
            // Kích hoạt tài khoản
            $user->is_active = 1;
            $user->save();
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 'Đăng ký thành công', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Registration error: ' . $th->getMessage());
            return $this->errorResponse('Đăng ký thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function logout(Request $request)
    {
        try {
            $user = $request->user(); // auth:sanctum

            if (!$user) {
                return $this->errorResponse('Người dùng không được xác thực.', Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->currentAccessToken();
            if ($token) {
                $token->delete();
            }

            return $this->successResponse([], 'Đăng xuất thành công.');
        } catch (\Throwable $th) {
            Log::error('Lỗi đăng xuất: ' . $th->getMessage());
            return $this->errorResponse('Đăng xuất không thành công. Vui lòng thử lại.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Email không hợp lệ.',
            ], 422);
        }

        $data = $validator->validated();

        $otp = rand(100000, 999999);

        $user = User::query()->where('email', $data['email'])->first();

        if (empty($user)) {
            // Trường hợp chưa có user thì tạo mới
            $create_user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'slug' => generateSlug($data['name']),
                'type' => User::TYPE_MEMBER,
                'is_active' => false
            ]);
            // Tạo OTP cho user mới tạo
            $create_user->oneTimePassword()->create([
                'otp' => $otp,
                'expires_at' => now()->addMinutes(1),
            ]);
        } else {
            // Trường hợp đã có user thì cập nhật lại thông tin và OTP 
            // Kiểm tra nếu user đã kích hoạt thì không cho phép gửi OTP
            if ($user->is_active) {
                return $this->errorResponse('Tài khoản đã được kích hoạt trước đó.', Response::HTTP_BAD_REQUEST);
            }
            // Cập nhật thông tin user
            $user->update([
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'slug' => generateSlug($data['name'])
            ]);
            // Cập nhật hoặc tạo mới OTP
            $user->oneTimePassword()->updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(1),
                ]
            );
        }

        broadcast(new OtpGenerated($otp, $data['email']));
        return $this->successResponse([
            'slug' => $user ? $user->slug : $create_user->slug,
            'user_id' => $user ? $user->id : $create_user->id,
            'name' => $data['name'],
            'email' => $data['email'],
        ], 'OTP đã được gửi đến email của bạn.');
    }


    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Email không hợp lệ.', Response::HTTP_BAD_REQUEST);
        }

        $email = $request->input('email');
        $user = User::query()->where('email', $email)->first();

        if (!$user) {
            return $this->errorResponse('Email không tồn tại.', Response::HTTP_NOT_FOUND);
        }

        // Tạo OTP mới
        $otp = rand(100000, 999999);
        $user->oneTimePassword()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(1),
            ]
        );

        broadcast(new OtpGenerated($otp, $email));
        return $this->successResponse([
            'email' => $email,
            'slug' => $user->slug,
        ], 'OTP đã được gửi đến email của bạn.');
    }

    public function resetPassword(Request $request)
    {

        /**
         * slug : Slug của người dùng
         * otp : Mã OTP mà người dùng nhập vào
         */
        try {

            $user = User::with(['oneTimePassword'])->where('slug', $request->slug)->first();
            if (!$user) {
                return $this->errorResponse('Người dùng không tồn tại.', Response::HTTP_NOT_FOUND);
            }
            if ($user && $user->oneTimePassword) {
                $otpRecord = $user->oneTimePassword;
                if ($otpRecord->otp !== $request->otp) {
                    return $this->errorResponse('Mã OTP không đúng.', Response::HTTP_BAD_REQUEST);
                }
                if ($otpRecord->expires_at < now()) {
                    return $this->errorResponse('Mã OTP đã hết hạn.', Response::HTTP_BAD_REQUEST);
                }
            } else {
                return $this->errorResponse('Mã OTP không hợp lệ.', Response::HTTP_BAD_REQUEST);
            }
            // Kích hoạt tài khoản
            $passsword = rand(10000000, 99999999);
            $user->password = bcrypt($passsword);
            $user->save();
            broadcast(new PasswordGenerated($passsword, $user->email));

            return $this->successResponse([], 'Mật khẩu đã được đặt lại thành công.', Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Registration error: ' . $th->getMessage());
            return $this->errorResponse('Đăng ký thất bại', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => ['required', 'string', 'exists:users,slug'],
            'key'  => ['required', 'in:register,forgot_password'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::query()->where('slug', $request->slug)->first();
        if (!$user) {
            return $this->errorResponse('Người dùng không tồn tại.', Response::HTTP_NOT_FOUND);
        }

        // Rule theo key
        if ($request->key === 'register') {
            if ($user->is_active) {
                return $this->errorResponse('Tài khoản đã được kích hoạt trước đó.', Response::HTTP_BAD_REQUEST);
            }
        }

        if ($request->key === 'forgot_password') {
            if (!$user->is_active) {
                return $this->errorResponse('Tài khoản chưa được kích hoạt nên không thể lấy lại mật khẩu.', Response::HTTP_BAD_REQUEST);
            }
        }

        // Rate limit resend (vd 30 giây) 
        $otpRow = $user->oneTimePassword()->first();
        if ($otpRow && $otpRow->updated_at && $otpRow->updated_at->gt(now()->subSeconds(30))) {
            return $this->errorResponse('Vui lòng chờ 30 giây rồi thử gửi lại OTP.', Response::HTTP_TOO_MANY_REQUESTS);
        }

        $otp = rand(100000, 999999);

        // Update/Insert OTP (1 record/user)
        $user->oneTimePassword()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(1),
            ]
        );

        // Gửi OTP (dùng chung event của bạn)
        broadcast(new OtpGenerated($otp, $user->email));

        return $this->successResponse([
            'slug' => $user->slug,
            'email' => $user->email,
            'key' => $request->key,
        ], 'OTP đã được gửi lại.');
    }
}
