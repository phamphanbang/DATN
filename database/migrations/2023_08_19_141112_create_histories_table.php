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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('duration');
            $table->enum('test_type', ['fulltest', 'practice']);
            $table->enum('exam_type', ['practice', 'test']);
            $table->integer('right_questions');
            $table->integer('total_questions');
            $table->integer('wrong_questions');
            $table->integer('score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
