<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('csp_logs', function (Blueprint $table) {
            $table->string('user_agent')->nullable()->after('ip_address');
            $table->boolean('is_read')->default(false)->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('csp_logs', function (Blueprint $table) {
            $table->dropColumn(['user_agent', 'is_read']);
        });
    }
};