<?php

namespace App\Http\Requests;

use App\Rules\SyncPartsAndTemplates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class TemplateCreateRequest extends FormRequest
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
            'name' => 'required|string|unique:templates,name|max:50',
            'duration' => 'required|string',
            'description' => 'required|string',
            'total_parts' => 'required|integer',
            'total_questions' => 'required|integer',
            'total_score' => 'required|integer',
            'status' => 'required|in:active,deactive',
            'parts.*.order_in_test' => 'required|integer',
            'parts.*.total_questions' => 'required|integer',
            'parts.*.part_type' => 'required|in:listening,reading',
            'parts.*.has_group_question' => 'required|boolean',
            'parts' => new SyncPartsAndTemplates
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('template.validation.name.required'),
            'name.string' => __('template.validation.name.string'),
            'name.unique' => __('template.validation.name.unique'),
            'name.max' => __('template.validation.name.max'),
            'description.required' => __('template.validation.description.required'),
            'description.string' => __('template.validation.description.string'),
            'duration.required' => __('template.validation.duration.required'),
            'duration.string' => __('template.validation.duration.string'),
            'total_parts.required' => __('template.validation.total_parts.required'),
            'total_parts.integer' => __('template.validation.total_parts.integer'),
            'total_questions.required' => __('template.validation.total_questions.required'),
            'total_questions.integer' => __('template.validation.total_questions.integer'),
            'total_score.required' => __('template.validation.total_score.required'),
            'total_score.integer' => __('template.validation.total_score.integer'),
            'status.required' => __('template.validation.status.required'),
            'status.in' => __('template.validation.status.in'),

            'parts.*.order_in_test.required' => __('template.validation.parts.order_in_test.required'),
            'parts.*.order_in_test.integer' => __('template.validation.parts.order_in_test.integer'),
            'parts.*.total_questions.required' => __('template.validation.parts.total_questions.required'),
            'parts.*.total_questions.integer' => __('template.validation.parts.total_questions.integer'),
            'parts.*.part_type.required' => __('template.validation.parts.part_type.required'),
            'parts.*.part_type.in' => __('template.validation.parts.part_type.in'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
