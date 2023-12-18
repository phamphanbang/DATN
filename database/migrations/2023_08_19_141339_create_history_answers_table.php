<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained(table:'history_parts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('question_id')->constrained(table:'exam_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('answer_id')->nullable()->constrained(table:'exam_answers')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('is_right');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_answers');
    }
};
