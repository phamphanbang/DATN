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
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained(table: 'test_parts')->onDelete('cascade');
            $table->foreignId('group_id')->constrained(table: 'test_group_questions')->onDelete('cascade');
            $table->foreignId('question_type_id')->constrained(table: 'question_types')->onDelete('cascade');
            $table->text('question');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
