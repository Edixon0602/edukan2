<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('item_name'); // Course or plan name
            $table->string('amount'); // Price string or decimal
            $table->string('payment_method'); // 'card', 'mobile', 'binance'
            $table->string('receipt_path')->nullable(); // Local receipt file path
            $table->string('status')->default('en_revision'); // 'en_revision', 'aprobado', 'rechazado'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_payments');
    }
};
