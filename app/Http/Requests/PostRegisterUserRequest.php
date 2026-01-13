<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRegisterUserRequest extends FormRequest
{
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|confirmed|min:5|max:255',
            'slug' => 'nullable|string|max:50|unique:users,slug',
            'type' => 'nullable|string|in:member,admin',
            'avatar' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|boolean',
            'birthday' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'auth_provider' => 'nullable|string',
            'auth_provider_id' => 'nullable|string',
        ];
    }
}
