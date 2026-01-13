<?php

namespace App\Http\Requests\Admin;

use App\Models\Capacity;
use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CapacityRequest extends FormRequest
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
            'cap_name' => ['required', 'string', 'max:255', Rule::unique(Capacity::class)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Capacity::class)],
            'is_active' => ['nullable', 'boolean']
        ];
    }

    public function rulesForUpdate()
    {
        $id = $this->route('capacity');
        return [
            'cap_name' => ['required', 'string', 'max:255', Rule::unique(Capacity::class)->ignore($id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Capacity::class)->ignore($id)],
            'is_active' => ['nullable', 'boolean']
        ];
    }

    public function messages()
    {
        return [
            'cap_name.required' => 'Vui lòng nhập tên dung lượng.',
            'cap_name.unique' => 'Tên dung lượng đã tồn tại.',
            'cap_name.max' => 'Tên dung lượng không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
        ];
    }
}