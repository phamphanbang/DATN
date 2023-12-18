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
        Schema::create('history_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')->constrained(table: 'histories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('part_id')->constrained(table: 'exam_parts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('order_in_test');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_parts');
    }
};
