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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('doctor_id')
                ->references('id')
                ->on('staff_users')
                ->onDelete('cascade');

            $table->foreignUuid('clinic_id')
                ->references('id')
                ->on('clinics')
                ->onDelete('cascade');

            $table->enum('day_of_week', [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ]);

            $table->time('start_time');

            $table->time('end_time');

            $table->integer('slot_duration_minutes');

            $table->date('effective_from');

            $table->date('effective_until')
                ->nullable();

            $table->boolean('is_temporary')
                ->default(false);

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('doctor_id');
            $table->index('clinic_id');
            $table->index('day_of_week');
            $table->index('effective_from');
            $table->index('effective_until');
            $table->index('is_temporary');
            $table->index('created_at');
            $table->index('updated_at');

            // Unique Constraints
            $table->unique(
                ['tenant_id', 'doctor_id', 'clinic_id', 'day_of_week', 'start_time', 'effective_from'],
                'doctor_schedules_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
