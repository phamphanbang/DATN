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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained(table: 'exam_parts')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained(table: 'exam_groups')->onDelete('cascade');
            $table->foreignId('question_type_id')->nullable()->constrained(table: 'question_types')->onDelete('cascade');
            $table->text('question')->nullable()->default(null);
            $table->string('audio')->nullable()->default(null);
            $table->string('attachment')->nullable()->default(null);
            $table->string('order_in_test');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
