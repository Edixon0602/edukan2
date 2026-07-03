<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('written_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->text('question');
            $table->text('student_answer');
            $table->string('status')->default('pendiente'); // 'pendiente', 'corregida'
            $table->integer('final_grade')->nullable(); // Grade assigned by admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('written_answers');
    }
};
