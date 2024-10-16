<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
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
            'order_user_name' => 'required',
            'order_user_email' => 'required',
            'order_user_phone' => 'required',
            'order_user_address' => 'required',
            'order_user_note' => 'nullable',
            'method_payment' => 'required',
            'order_total_price' => 'required',
        ];
    }
}
