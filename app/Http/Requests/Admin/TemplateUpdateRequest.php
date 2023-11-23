<?php

namespace App\Http\Requests\Admin;

use App\Rules\SyncPartsAndTemplates;
use App\Http\Requests\BaseRequest;
use Illuminate\Http\Response;

class TemplateUpdateRequest extends BaseRequest
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
        $rules = [
            'name' => 'required|string|unique:templates,name,' . request()->route('template') . '|max:50',
            'duration' => 'required|string',
            'description' => 'required|string',
            'total_parts' => 'required|integer',
            'total_questions' => 'required|integer',
            'total_score' => 'required|integer',
            'status' => 'required|in:active,disable',
            'parts' => new SyncPartsAndTemplates,
        ];

        foreach ($this['parts'] as $key => $part) {
            $part_rules = [
                'parts.' . $key . '.order_in_test' => 'required|integer',
                'parts.' . $key . '.num_of_questions' => 'required|integer',
                'parts.' . $key . '.part_type' => 'required|in:listening,reading',
                'parts.' . $key . '.has_group_question' => 'required|string',
            ];
            $rules = array_merge($rules, $part_rules);
        }
        return $rules;
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
}
