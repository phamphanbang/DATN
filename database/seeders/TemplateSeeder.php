<?php

namespace Database\Seeders;

use App\Models\Template;
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
            ->has(TemplatePart::factory()->part_2(), 'parts')
            ->has(TemplatePart::factory()->part_3(), 'parts')
            ->has(TemplatePart::factory()->part_4(), 'parts')
            ->count(4)
            ->create();
    }
}
