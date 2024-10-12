<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddCartRequest extends FormRequest
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
            'color_id' => 'required',
            'capacity_id' => 'required',
        ];
    }
    public function messages(){
        return [
            'color_id.required' => 'Vui lòng chọn dung lượng !!!',
            'capacity_id.required' => 'Vui lòng chọn màu !!!',
        ];
    }
}
