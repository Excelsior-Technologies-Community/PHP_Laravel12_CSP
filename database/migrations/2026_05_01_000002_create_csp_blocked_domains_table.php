<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csp_blocked_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('action')->default('block');
            $table->text('reason')->nullable();
            $table->integer('hit_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csp_blocked_domains');
    }
};