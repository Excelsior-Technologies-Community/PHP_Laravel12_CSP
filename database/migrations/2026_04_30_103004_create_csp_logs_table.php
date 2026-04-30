<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('csp_logs', function (Blueprint $table) {
            $table->id();
            $table->text('document_uri')->nullable();
            $table->text('blocked_uri')->nullable();
            $table->string('violated_directive')->nullable();
            $table->string('effective_directive')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csp_logs');
    }
};