<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
            'coupon_code'       => ['required', 'min:5', Rule::unique(Coupon::class)->ignore($this->route('coupon'))],
            'discount_type'     => ['required', Rule::in(0, 1)],
            'discount_value'    => 'required|numeric|min:0',
            'start_date'        => 'required',
            'end_date'          => 'required',
            'coupon_limit'      => 'required|numeric|min:0',
            'coupon_used'      => 'nullable|numeric|min:0',
            'coupon_status'     => ['nullable', Rule::in(0, 1)],
            'coupon_description' => 'nullable',
            'product_id'        => 'nullable|array',
            'product_id.*'      => 'nullable|integer',
        ];
    }
}
