<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = 0;
        $a4 = [1,2,3,4];
        $a3 = [1,2,3];
        for($i = 1001;$i<=1005;$i++) {
            $k = array_rand($a4);
            DB::table('exam_answers')
                ->where('question_id', $i)
                ->where('order_in_question',$a4[$k])
                ->update([
                    'is_right' => true
                ]);
        }
        for($i = 1006;$i<=1030;$i++) {
            $k = array_rand($a3);
            DB::table('exam_answers')
                ->where('question_id', $i)
                ->where('order_in_question',$a3[$k])
                ->update([
                    'is_right' => true
                ]);
        }
        for($i = 1031;$i<=1200;$i++) {
            $k = array_rand($a4);
            DB::table('exam_answers')
                ->where('question_id', $i)
                ->where('order_in_question',$a4[$k])
                ->update([
                    'is_right' => true
                ]);
        }
    }
}
