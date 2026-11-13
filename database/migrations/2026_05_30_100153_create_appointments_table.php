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
        Schema::create('appointments', function (Blueprint $table) {
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

            $table->date('appointment_date');

            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', [
                'scheduled',
                'confirmed',
                'arrived',
                'in_consultation',
                'completed',
                'cancelled',
                'no_show',
            ])->default('scheduled');

            $table->enum('booking_type', [
                'online',
                'receptionist',
                'walk_in',
            ]);

            $table->foreignUuid('booked_by_user_id')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->text('cancellation_reason')->nullable();

            $table->boolean('reminder_24h_sent')->default(false);
            $table->boolean('reminder_2h_sent')->default(false);

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('clinic_id');
            $table->index('appointment_date');
            $table->index('status');
            $table->index('booking_type');
            $table->index('booked_by_user_id');
            $table->index('reminder_24h_sent');
            $table->index('reminder_2h_sent');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};