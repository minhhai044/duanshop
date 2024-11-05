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
            'coupon_code'       => ['required', Rule::unique(Coupon::class)->ignore($this->route('coupon'))],
            'discount_type'     => 'required',
            'discount_value'    => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required',
            'coupon_limit'      => 'required',
            'coupon_exist'      => 'nullable',
            'coupon_status'     => 'nullable',
            'coupon_description'=> 'nullable',
        ];
    }
}
