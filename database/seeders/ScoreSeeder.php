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

        Score::factory()->create([
            'questions' => 0,
            'type' => config('enum.part_type.LISTENING'),
            'score' => 5
        ]);
        for ($i = 1; $i <= 95; $i++) {
            Score::factory()->create([
                'questions' => $i,
                'type' => config('enum.part_type.LISTENING'),
                'score' => 10 + $i*5
            ]);
        }
        for ($i = 96; $i <= 100; $i++) {
            Score::factory()->create([
                'questions' => $i,
                'type' => config('enum.part_type.LISTENING'),
                'score' => 495
            ]);
        }


        for ($i = 0; $i <= 2; $i++) {
            Score::factory()->create([
                'questions' => $i,
                'type' => config('enum.part_type.READING'),
                'score' => 5
            ]);
        }
        for ($i = 3; $i <= 100; $i++) {
            Score::factory()->create([
                'questions' => $i,
                'type' => config('enum.part_type.READING'),
                'score' => $i*5 - 5
            ]);
        }

        // for ($i = 0; $i <= $num_of_question; $i++) {
        //     Score::factory()->create([
        //         'questions' => $i,
        //         'type' => config('enum.part_type.LISTENING'),
        //         'score' => $i * 5
        //     ]);
        //     Score::factory()->create([
        //         'questions' => $i,
        //         'type' => config('enum.part_type.READING'),
        //         'score' => $i * 5
        //     ]);
        // }
    }
}
