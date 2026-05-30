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
        Schema::create('consultations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreignUuid('doctor_id')
                ->references('id')
                ->on('staff_users')
                ->onDelete('cascade');

            $table->foreignUuid('clinic_id')
                ->references('id')
                ->on('clinics')
                ->onDelete('cascade');

            $table->foreignUuid('appointment_id')
                ->nullable()
                ->references('id')
                ->on('appointments')
                ->nullOnDelete();

            $table->date('consultation_date');

            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->text('chief_complaint')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('consultation_notes')->nullable();

            $table->dateTime('services_confirmed_at')->nullable();

            $table->foreignUuid('services_confirmed_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->integer('triage_bp_systolic')->nullable();
            $table->integer('triage_bp_diastolic')->nullable();

            $table->decimal('triage_temperature', 4, 1)->nullable();
            $table->decimal('triage_weight', 5, 1)->nullable();

            $table->integer('triage_heart_rate')->nullable();

            $table->foreignUuid('triage_recorded_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('clinic_id');
            $table->index('appointment_id');
            $table->index('consultation_date');
            $table->index('services_confirmed_at');
            $table->index('services_confirmed_by');
            $table->index('triage_recorded_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};