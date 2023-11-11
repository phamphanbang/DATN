<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ScoreUpdateRequest extends BaseRequest
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
            'questions' => 'required|unique:scores,questions,' . request()->route('score') . ',questions|integer',
            'type' => 'required|string|in:listening,reading',
            'score' => 'required|integer'
        ];
    }
}
