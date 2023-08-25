<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Template>
 */
class TemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Factory template',
            'description' => 'This is a template generate by factory',
            'duration' => '120',
            'total_parts' => 7,
            'total_questions' => 200,
            'total_score' => 990,
            'status' => config('enum.template_status.ACTIVE')
        ];
    }
}
