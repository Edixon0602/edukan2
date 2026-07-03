<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('membership')->default('regular'); // 'regular', 'estandar', 'pro', 'vip'
            $table->string('origin')->nullable(); // 'Instagram', 'YouTube', etc.
            $table->integer('global_discount')->default(0); // 0-100%
            $table->string('avatar_path')->nullable();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'phone',
                'country',
                'role_id',
                'membership',
                'origin',
                'global_discount',
                'avatar_path',
                'last_login_at',
            ]);
        });
    }
};
