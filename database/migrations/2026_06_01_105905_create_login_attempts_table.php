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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('attempted_email');

            // No FK constraint because the user may not exist
            $table->uuid('matched_user_id')
                ->nullable();

            $table->string('ip_address', 45)
                ->nullable();

            $table->string('device_info')
                ->nullable();

            $table->boolean('success');

            $table->string('failure_reason')
                ->nullable();

            $table->timestamp('attempted_at')
                ->useCurrent();

            // Indexes
            $table->index('attempted_email');
            $table->index('matched_user_id');
            $table->index('success');
            $table->index('attempted_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};