<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ExamUpdateRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:exams,name,' . request()->route('exam') . '|string|max:50|min:4',
            'status' => 'required|in:active,disable',
            'type' => 'required|in:practice,test',
            'audio' => "nullable|string",
        ];
    }
}
