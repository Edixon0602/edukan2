<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->text('question');
            $table->string('type'); // 'seleccion', 'verdadero_falso', 'desarrollo'
            $table->json('options')->nullable(); // JSON object for options (e.g. {"A": "Option A", "B": "Option B"})
            $table->string('correct_answer')->nullable(); // 'A', 'B', 'Verdadero', etc.
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
