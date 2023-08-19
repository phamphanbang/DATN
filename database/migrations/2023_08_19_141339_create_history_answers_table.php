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
            $table->foreignId('history_id')->constrained(table:'histories')->onDelete('cascade');
            $table->foreignId('answer_id')->constrained(table:'test_answers')->onDelete('cascade');
            $table->foreignId('question_id')->constrained(table:'test_questions')->onDelete('cascade');
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
