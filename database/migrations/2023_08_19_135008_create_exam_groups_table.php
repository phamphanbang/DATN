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
            $table->foreignId('part_id')->constrained(table: 'exam_parts')->onUpdate('cascade')->onDelete('cascade');
            $table->text('question')->nullable()->default(null);
            $table->integer('order_in_part');
            $table->integer('from_question');
            $table->integer('to_question');
            $table->string('attachment')->nullable()->default(null);
            $table->string('audio')->nullable()->default(null);
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
