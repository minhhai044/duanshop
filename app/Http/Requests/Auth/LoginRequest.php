<?php

namespace App\Http\Requests\Auth;


use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use ApiRequestJsonTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {


        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }

    public function rulesForCreate()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6'],
            'remember' => ['boolean']
            // nếu bạn login bằng username/sdt thì thêm field tương ứng ở đây
        ];
    }

    public function rulesForUpdate()
    {
        return [];
    }

    // messages chung
    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu :min ký tự.',
        ];
    }

    /**
     * Get the body parameters for Scribe documentation
     */
    public function bodyParameters()
    {
        return [
            'email' => [
                'description' => 'User email address',
                'example' => 'user@example.com',
            ],
            'password' => [
                'description' => 'User password (minimum 6 characters)',
                'example' => 'password123',
            ],
            'remember' => [
                'description' => 'Remember login session',
                'example' => true,
            ],
        ];
    }
}
