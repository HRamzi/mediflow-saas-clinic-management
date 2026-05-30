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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('consultation_id')
                ->references('id')
                ->on('consultations')
                ->onDelete('cascade');

            $table->foreignUuid('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreignUuid('doctor_id')
                ->references('id')
                ->on('staff_users')
                ->onDelete('cascade');

            $table->foreignUuid('medication_id')
                ->references('id')
                ->on('medication_master')
                ->onDelete('restrict');

            $table->string('dosage', 100);

            $table->string('frequency', 100);

            $table->integer('duration_days');

            $table->text('instructions')->nullable();

            $table->boolean('dispensed')->default(false);

            $table->foreignUuid('dispensed_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->dateTime('dispensed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('consultation_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('medication_id');
            $table->index('dispensed');
            $table->index('dispensed_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};