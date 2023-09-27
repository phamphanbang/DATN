<?php

namespace App\Http\Requests;

use App\Rules\SyncPartsAndTemplates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class TemplateUpdateRequest extends FormRequest
{
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
            'name' => 'required|string|unique:templates,name,' . request()->route('template') . '|max:50',
            'description' => 'required|string',
            'status' => 'required|in:active,deactive'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required',
            'name.string' => 'The name must be a string',
            'name.unique' => 'The name must be unique',
            'name.max' => 'The name has max length of 50',
            'description.required' => 'The description is required',
            'description.string' => 'The description must be a string',
            'status.required' => 'The status is required',
            'status.in' => 'The status must be one of these value : active , deactive'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
