<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamCreateRequest extends FormRequest
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
        $rules =  [
            "name" => "required|unique:exams,name|string|max:50|min:4",
            "template_id" => "required|exists:templates,id"
        ];

        foreach ($this["parts"] as $key => $part) {
            $part_rules = [
                "parts.$key.order_in_test" => "required|integer",
                "parts.$key.num_of_answers" => "required|integer",
                "parts.$key.num_of_questions" => "required|integer",
                "parts.$key.part_type" => "required|string|in:reading,listening",
                "parts.$key.has_group_question" => "required|boolean",
                "parts.$key.groups" => "required_if:parts.$key.has_group_question,true"
            ];
            if ($part["has_group_question"]) {
                foreach ($part["groups"] as $group_key => $group) {
                    $group_rules = [
                        "parts.$key.groups.$group_key.order_in_part" => "required|integer",
                        "parts.$key.groups.$group_key.from_question" => "required|integer",
                        "parts.$key.groups.$group_key.to_question" => "required|integer"
                    ];
                    $part_rules = array_merge($part_rules, $group_rules);
                }
            }
            $rules = array_merge($rules, $part_rules);
        }

        return $rules;
    }

    public function messages()
    {
        $messages =  [
            "name.required" => "The name field is required.",
            "name.unique" => "The name has already been taken.",
            "name.string" => "The name must be a string.",
            "name.max" => "The name must not exceed 50 characters.",
            "name.min" => "The name must be at least 4 characters.",
            "template_id.required" => "The template ID field is required.",
            "template_id.exists" => "The selected template ID is invalid.",
        ];

        foreach ($this["parts"] as $key => $part) {
            $part_message = [
                "parts.$key.order_in_test.required" => "The order_in_test for part $key is required.",
                "parts.$key.order_in_test.integer" => "The order_in_test for part $key must be an integer.",
            ];
    
            if ($part["has_group_question"]) {
                foreach ($part["groups"] as $group_key => $group) {
                    $group_message = [
                        "parts.$key.groups.$group_key.order_in_part.required" => "The order_in_part for group $group_key in part $key is required.",
                        "parts.$key.groups.$group_key.order_in_part.integer" => "The order_in_part for group $group_key in part $key must be an integer.",
                    ];
    
                    $messages = array_merge($messages, $group_message);
                }
            }
    
            $messages = array_merge($messages, $part_message);
        }
    
        return $messages;
    }
}
