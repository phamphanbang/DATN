<?php

namespace Database\Seeders;

use App\Models\Score;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $num_of_question = 20;
        for ($i = 0; $i < $num_of_question; $i++) {
            Score::factory()->create([
                'questions' => $i,
                'type' => config('enum.part_type.LISTENING'),
                'score' => $i * 5
            ]);
            Score::factory()->create([
                'questions' => $i,
                'type' => config('enum.part_type.READING'),
                'score' => $i * 5
            ]);
        }
    }
}
