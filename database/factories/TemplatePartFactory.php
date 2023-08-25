<?php

namespace Database\Factories;

use App\Models\TemplatePart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemplatePart>
 */
class TemplatePartFactory extends Factory
{
    protected $model = TemplatePart::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_in_test' => rand(1,9),
            'total_questions' => 10,
            'part_type' => config('enum.part_type.LISTENING'),
            'has_group_question' => false
        ];
    }
}
