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
        Schema::create('exam_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained(table: 'exam_parts')->onDelete('cascade');
            $table->text('question');
            $table->integer('from_question');
            $table->integer('to_question');
            $table->string('attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_groups');
    }
};
