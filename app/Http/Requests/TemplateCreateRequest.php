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
        $rules = [
            'name' => 'required|string|unique:templates,name|max:50',
            'duration' => 'required|string',
            'description' => 'required|string',
            'total_parts' => 'required|integer',
            'total_questions' => 'required|integer',
            'total_score' => 'required|integer',
            'status' => 'required|in:active,deactive',
            'parts' => new SyncPartsAndTemplates,
        ];

        foreach ($this['parts'] as $key => $part) {
            $part_rules = [
                'parts.' . $key . '.order_in_test' => 'required|integer',
                'parts.' . $key . '.num_of_questions' => 'required|integer',
                'parts.' . $key . '.part_type' => 'required|in:listening,reading',
                'parts.' . $key . '.has_group_question' => 'required|boolean',
            ];
            $rules = array_merge($rules, $part_rules);
        }
        return $rules;
    }

    public function messages()
    {
        $message = [
            'name.required' => 'The name is required',
            'name.string' => 'The name must be a string',
            'name.unique' => 'The name must be unique',
            'name.max' => 'The name has max length of 50',
            'description.required' => 'The description is required',
            'description.string' => 'The description must be a string',
            'duration.required' =>  'The duration is required',
            'duration.string' => 'The duration must be a string',
            'total_parts.required' => 'The total_parts is required',
            'total_parts.integer' => 'The total_parts must be a number',
            'total_questions.required' => 'The total_questions is required',
            'total_questions.integer' => 'The total_questions must be a number',
            'total_score.required' => 'The total_score is required',
            'total_score.integer' => 'The total_score must be a number',
            'status.required' => 'The status is required',
            'status.in' => 'The status must be one of these value : active , deactive'
        ];

        foreach ($this['parts'] as $key => $part) {
            $part_message = [
                'parts.' . $key . '.order_in_test.required' => 'The order_in_test of part ' . $key . ' is required',
                'parts.' . $key . '.order_in_test.integer' => 'The order_in_test of part ' . $key . ' must be a number',
                'parts.' . $key . '.num_of_questions.required' => 'The num_of_questions of part ' . $key . ' is required',
                'parts.' . $key . '.num_of_questions.integer' => 'The num_of_questions of part ' . $key . ' must be a number',
                'parts.' . $key . '.part_type.required' => 'The part_type of part ' . $key . ' is required',
                'parts.' . $key . '.part_type.in' => 'The part_type of part ' . $key . ' must be one of these value : listening , reading'
            ];
            $message = array_merge($message, $part_message);
        }
        return $message;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
