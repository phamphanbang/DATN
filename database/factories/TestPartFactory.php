<?php

namespace Database\Factories;

use App\Models\TestPart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestPart>
 */
class TestPartFactory extends Factory
{
    protected $model = TestPart::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_in_test' => fake()->numberBetween(1,7),
            'total_questions' => fake()->numberBetween(10,20),
            'part_type' => config('enum.part_type.LISTENING'),
            'has_group_question' => false
        ];
    }
}
