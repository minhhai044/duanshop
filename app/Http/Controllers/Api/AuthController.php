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

/**
 * @group Authentication
 * 
 * APIs for managing user authentication
 */
class AuthController extends Controller
{

    use ApiResponseTrait;
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * User Login
     * 
     * Authenticate user and return access token
     * 
     * @bodyParam email string required User email. Example: user@example.com
     * @bodyParam password string required User password. Example: password123
     * @bodyParam remember boolean Remember login session. Example: true
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Đăng nhập thành công!",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com"
     *     },
     *     "access_token": "1|abc123...",
     *     "token_type": "Bearer"
     *   }
     * }
     * 
     * @response 401 {
     *   "status": false,
     *   "message": "Thông tin đăng nhập không đúng hoặc tài khoản bị khóa."
     * }
     */
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

    /**
     * Complete Registration
     * 
     * Complete user registration by verifying OTP
     * 
     * @bodyParam slug string required User slug from verify-otp response. Example: john-doe-123
     * @bodyParam otp string required OTP code sent to email. Example: 123456
     * 
     * @response 201 {
     *   "status": true,
     *   "message": "Đăng ký thành công",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "user@example.com"
     *     },
     *     "access_token": "1|abc123...",
     *     "token_type": "Bearer"
     *   }
     * }
     * 
     * @response 400 {
     *   "status": false,
     *   "message": "Mã OTP không đúng."
     * }
     */
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


    /**
     * User Logout
     * 
     * Logout user and revoke access token
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "Đăng xuất thành công.",
     *   "data": []
     * }
     * 
     * @response 401 {
     *   "status": false,
     *   "message": "Người dùng không được xác thực."
     * }
     */
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


    /**
     * Verify OTP and Create Account
     * 
     * Send OTP to email for account verification
     * 
     * @bodyParam email string required User email. Example: user@example.com
     * @bodyParam name string required User full name. Example: John Doe
     * @bodyParam password string required User password (min 8 chars). Example: password123
     * @bodyParam password_confirmation string required Password confirmation. Example: password123
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "OTP đã được gửi đến email của bạn.",
     *   "data": {
     *     "slug": "john-doe-123",
     *     "user_id": 1,
     *     "name": "John Doe",
     *     "email": "user@example.com"
     *   }
     * }
     * 
     * @response 422 {
     *   "status": false,
     *   "message": "Email không hợp lệ."
     * }
     */
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


    /**
     * Forgot Password
     * 
     * Send OTP to email for password reset
     * 
     * @bodyParam email string required User email. Example: user@example.com
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "OTP đã được gửi đến email của bạn.",
     *   "data": {
     *     "email": "user@example.com",
     *     "slug": "john-doe-123"
     *   }
     * }
     * 
     * @response 404 {
     *   "status": false,
     *   "message": "Email không tồn tại."
     * }
     */
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

    /**
     * Reset Password
     * 
     * Reset user password using OTP verification
     * 
     * @bodyParam slug string required User slug from forgot-password response. Example: john-doe-123
     * @bodyParam otp string required OTP code sent to email. Example: 123456
     * 
     * @response 201 {
     *   "status": true,
     *   "message": "Mật khẩu đã được đặt lại thành công.",
     *   "data": []
     * }
     * 
     * @response 400 {
     *   "status": false,
     *   "message": "Mã OTP không đúng."
     * }
     */
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




    /**
     * Resend OTP
     * 
     * Resend OTP code to user email
     * 
     * @bodyParam slug string required User slug. Example: john-doe-123
     * @bodyParam key string required OTP type (register or forgot_password). Example: register
     * 
     * @response 200 {
     *   "status": true,
     *   "message": "OTP đã được gửi lại.",
     *   "data": {
     *     "slug": "john-doe-123",
     *     "email": "user@example.com",
     *     "key": "register"
     *   }
     * }
     * 
     * @response 429 {
     *   "status": false,
     *   "message": "Vui lòng chờ 30 giây rồi thử gửi lại OTP."
     * }
     */
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
