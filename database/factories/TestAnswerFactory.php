<?php

namespace Database\Factories;

use App\Models\TestAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestAnswer>
 */
class TestAnswerFactory extends Factory
{
    protected $model = TestAnswer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_in_question' => fake()->numberBetween(1,4),
            'answer' => 'A.'
        ];
    }
}
