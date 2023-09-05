<?php

namespace Database\Factories;

use App\Models\TemplateGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemplateGroup>
 */
class TemplateGroupFactory extends Factory
{
    protected $model = TemplateGroup::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_in_part' => fake()->numberBetween(1,10),
            'total_questions' => 4,
            'from_question' => 1,
            'to_question' => 4
        ];
    }
}
