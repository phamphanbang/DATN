<?php

namespace Database\Factories;

use App\Models\TestGroupQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestGroupQuestion>
 */
class TestGroupQuestionFactory extends Factory
{
    protected $model = TestGroupQuestion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_in_part' => fake()->numberBetween(1,10),
            'question' => fake()->text(50),
            'attachment' => fake()->text(10)
        ];
    }
}
