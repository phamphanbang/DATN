<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'template_id' => Template::all()->random(1)->first()->id,
            'total_views' => fake()->numberBetween(1,100),
            'status' => config('enum.test_status.ACTIVE'),
            'panel' => 'default'
        ];
    }
}
