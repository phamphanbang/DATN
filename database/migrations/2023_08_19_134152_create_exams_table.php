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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->unique();
            $table->integer('total_views')->default(0);
            $table->enum('status', ['active', 'disable'])->default('disable');
            $table->enum('type', ['practice', 'test'])->default('practice');
            $table->string('audio')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
