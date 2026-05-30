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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('consultation_id')
                ->unique()
                ->references('id')
                ->on('consultations')
                ->onDelete('cascade');

            $table->foreignUuid('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreignUuid('clinic_id')
                ->references('id')
                ->on('clinics')
                ->onDelete('cascade');

            $table->uuid('insurance_claim_id')->nullable();

            $table->dateTime('generated_at');

            $table->decimal('subtotal', 10, 2);

            $table->decimal('discount_amount', 10, 2)
                ->default(0);

            $table->foreignUuid('discount_approved_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->decimal('total_amount', 10, 2);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'partially_paid',
                'voided',
            ])->default('pending');

            $table->enum('payment_method', [
                'cash',
                'card',
            ])->nullable();

            $table->dateTime('paid_at')->nullable();

            $table->foreignUuid('paid_by_receptionist_id')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->boolean('is_locked')->default(false);

            $table->foreignUuid('unlock_authorized_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('consultation_id');
            $table->index('patient_id');
            $table->index('clinic_id');
            $table->index('insurance_claim_id');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('generated_at');
            $table->index('paid_at');
            $table->index('is_locked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};