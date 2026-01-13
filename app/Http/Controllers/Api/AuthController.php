<?php

namespace App\Http\Controllers\Api;

use App\Events\OtpGenerated;
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
         * user_id
         * otp : Mã OTP mà người dùng nhập vào
         */
        try {

            $user = User::with(['oneTimePassword'])->find($request->user_id);
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
                return $this->errorResponse([], 'Tài khoản đã được kích hoạt trước đó.', Response::HTTP_BAD_REQUEST);
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
        return $this->successResponse([], 'OTP đã được gửi đến email của bạn.');
    }
}
