<?php

namespace Database\Factories;

use App\Models\ExamAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAnswer>
 */
class ExamAnswerFactory extends Factory
{
    protected $model = ExamAnswer::class;
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
