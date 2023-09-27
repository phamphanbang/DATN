<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamUpdateRequest extends FormRequest
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
            $base_part_rule = 'parts.' . $key;
            $part_rules = [
                $base_part_rule . '.id' => 'required|integer',
            ];
            if ($part["groups"]) {
                foreach ($part["groups"] as $group_key => $group) {
                    $base_group_rule = $base_part_rule . '.groups.' . $group_key;
                    $group_rules = [
                        $base_group_rule . '.id' => 'required|integer',
                    ];
                    foreach ($group['questions'] as $question_key => $question) {
                        $base_question_rule = $base_group_rule . '.questions.' . $question_key;
                        $question_rules = [
                            $base_question_rule . '.id'  => 'required|integer',
                            $base_question_rule . '.question_type_id'  => 'integer',
                            $base_question_rule . '.question'  => 'required|string',
                            $base_question_rule . '.attachment'  => 'required|string',
                            $base_question_rule . '.audio'  => 'required|string',
                        ];
                        foreach ($question['answers'] as $answer_key => $answer) {
                            $base_answer_rule = $base_question_rule . '.answers.' . $answer_key;
                            $answer_rules = [
                                $base_answer_rule . '.id' => 'required|integer',
                                $base_answer_rule . '.answer' => 'required|integer',
                                $base_answer_rule . '.is_right' => 'required|boolean'
                            ];
                            $question_rules = array_merge($$question_rules, $$answer_rules);
                        }
                        $group_rules = array_merge($group_rules, $question_rules);
                    }
                    $part_rules = array_merge($part_rules, $group_rules);
                }
            } else {
                foreach ($part['questions'] as $question_key => $question) {
                    $base_question_rule = $base_part_rule . '.questions.' . $question_key;
                    $question_rules = [
                        $base_question_rule . '.id'  => 'required|integer',
                        $base_question_rule . '.question_type_id'  => 'integer',
                        $base_question_rule . '.question'  => 'required|string',
                        $base_question_rule . '.attachment'  => 'required|string',
                        $base_question_rule . '.audio'  => 'required|string',
                    ];
                    foreach ($question['answers'] as $answer_key => $answer) {
                        $base_answer_rule = $base_question_rule . '.answers.' . $answer_key;
                        $answer_rules = [
                            $base_answer_rule . '.id' => 'required|integer',
                            $base_answer_rule . '.answer' => 'required|integer',
                            $base_answer_rule . '.is_right' => 'required|boolean'
                        ];
                        $question_rules = array_merge($$question_rules, $$answer_rules);
                    }
                    $part_rules = array_merge($part_rules, $question_rules);
                }
            }
            $rules = array_merge($rules, $part_rules);
        }

        return $rules;
    }
}
