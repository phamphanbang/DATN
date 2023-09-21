<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SyncPartsAndTemplates implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $total_parts = request('total_parts');
        $parts_from_request = count($value);
        if($total_parts !== $parts_from_request) {
            $fail(__('template.validation.parts.partsEqualTotalParts'));
        }

        $total_questions = request('total_questions');
        $questions_from_request = 0;
        foreach($value as $part) {
            $questions_from_request += $part['num_of_questions'];
        }
        if($total_questions !== $questions_from_request) {
            $fail(__('template.validation.parts.questionsEqualTotalQuestions'));
        }
    }
}
