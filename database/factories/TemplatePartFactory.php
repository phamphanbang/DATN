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
            'name' => fake()->name(),
            'order_in_test' => rand(1,9),
            'total_questions' => 10,
            'part_type' => config('enum.part_type.LISTENING'),
            'has_group_question' => false
        ];
    }

    public function part_1()
    {
        return $this->state(function () {
            return [
                'name' => 'Part 1',
                'order_in_test' => 1,
                'total_questions' => 10,
                'part_type' => config('enum.part_type.LISTENING'),
                'has_group_question' => false
            ];
        });
    }

    public function part_2()
    {
        return $this->state(function () {
            return [
                'name' => 'Part 2',
                'order_in_test' => 2,
                'total_questions' => 10,
                'part_type' => config('enum.part_type.LISTENING'),
                'has_group_question' => true
            ];
        });
    }

    public function part_3()
    {
        return $this->state(function () {
            return [
                'name' => 'Part 3',
                'order_in_test' => 3,
                'total_questions' => 10,
                'part_type' => config('enum.part_type.READING'),
                'has_group_question' => false
            ];
        });
    }

    public function part_4()
    {
        return $this->state(function () {
            return [
                'name' => 'Part 4',
                'order_in_test' => 4,
                'total_questions' => 10,
                'part_type' => config('enum.part_type.READING'),
                'has_group_question' => true
            ];
        });
    }

}
