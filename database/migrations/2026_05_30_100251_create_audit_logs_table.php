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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('user_id')
                ->references('id')
                ->on('staff_users')
                ->onDelete('cascade');

            $table->foreignUuid('clinic_id')
                ->nullable()
                ->references('id')
                ->on('clinics')
                ->nullOnDelete();

            $table->enum('log_type', [
                'billing_action',
                'patient_access',
                'login_event',
                'admin_change',
                'discount_request',
                'access_revocation',
            ]);

            $table->string('entity_type', 100);

            $table->uuid('entity_id');

            $table->string('action');

            $table->json('original_value')->nullable();

            $table->json('new_value')->nullable();

            $table->string('ip_address', 45)->nullable();

            $table->string('device_info')->nullable();

            $table->timestamp('created_at');

            // Indexes
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('clinic_id');
            $table->index('log_type');
            $table->index('entity_type');
            $table->index('entity_id');
            $table->index('action');
            $table->index('created_at');

            // Polymorphic lookup optimization
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};