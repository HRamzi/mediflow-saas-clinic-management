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
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');

            $table->foreignUuid('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->string('insurance_provider');

            $table->decimal('claim_amount', 10, 2);

            $table->enum('status', [
                'pending',
                'submitted',
                'approved',
                'rejected',
                'resubmitted',
            ])->default('pending');

            $table->dateTime('submitted_at')
                ->nullable();

            $table->dateTime('decision_at')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->text('document_url')
                ->nullable();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('invoice_id');
            $table->index('patient_id');
            $table->index('insurance_provider');
            $table->index('status');
            $table->index('submitted_at');
            $table->index('decision_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
    }
};