<?php

namespace App\Http\Requests\Admin;

use App\Models\Capacity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoteCapacityRequest extends FormRequest
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
            'cap_name' => ['required',Rule::unique(Capacity::class)]
        ];
    }
}
