<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\TemplateGroup;
use App\Models\TemplatePart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Template::factory()
            ->has(TemplatePart::factory()->part_1(), 'parts')
            ->has(TemplatePart::factory()->part_2()
                ->has(TemplateGroup::factory()->state(function () {
                    return [
                        'order_in_part' => 1,
                        'total_questions' => 5,
                        'from_question' => 11,
                        'to_question' => 15
                    ];
                }), 'groups')
                ->has(TemplateGroup::factory()->state(function () {
                    return [
                        'order_in_part' => 2,
                        'total_questions' => 5,
                        'from_question' => 16,
                        'to_question' => 20
                    ];
                }), 'groups'), 'parts')
            ->has(TemplatePart::factory()->part_3(), 'parts')
            ->has(TemplatePart::factory()->part_4()
                ->has(TemplateGroup::factory()->state(function () {
                    return [
                        'order_in_part' => 1,
                        'total_questions' => 5,
                        'from_question' => 31,
                        'to_question' => 35
                    ];
                }), 'groups')
                ->has(TemplateGroup::factory()->state(function () {
                    return [
                        'order_in_part' => 2,
                        'total_questions' => 5,
                        'from_question' => 36,
                        'to_question' => 40
                    ];
                }), 'groups'), 'parts')
            ->count(4)
            ->create();
    }
}
