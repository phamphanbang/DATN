<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\Test;
use App\Models\TestPart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = Template::all()->random(1)->first();
        $test = Test::factory();
        foreach($template->parts as $part) {
            $test_part = $this->getPartFactory($part);
        }
    }

    public function getPartFactory($part) 
    {
        return TestPart::factory()->state(function (array $attributes) use ($part) {
            return [
                'order_in_test' => $part->order_in_test,
                'total_questions' => $part->total_questions,
                'part_type' => $part->part_type,
                'has_group_question' => $part->has_group_question
            ];
        });
    }
}
