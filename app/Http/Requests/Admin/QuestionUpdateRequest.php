<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class QuestionUpdateRequest extends BaseRequest
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
            'question' => 'nullable|string|max:100',
            'attachment' => $this->getValidationImg('attachment'),
            'audio' => $this->getValidationAudio('audio')
        ];
    }

    public function getValidationAudio(String $key): string
    {
        if (request()->hasFile($key)) {
            return "nullable|mimes:mp3";
        }
        return "nullable|string";
    }

    public function getValidationImg(String $key): string
    {
        if (request()->hasFile($key)) {
            return "nullable|mimes:png,jpg";
        }
        return "nullable|string";
    }
}
