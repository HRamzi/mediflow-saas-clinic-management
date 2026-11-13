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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreignUuid('consultation_id')
                ->nullable()
                ->references('id')
                ->on('consultations')
                ->nullOnDelete();

            $table->enum('result_type', [
                'laboratory',
                'radiology'
            ]);

            $table->string('test_name');

            $table->foreignUuid('uploaded_by')
                ->references('id')
                ->on('staff_users')
                ->onDelete('cascade');

            $table->dateTime('uploaded_at');

            $table->text('result_file_url');

            $table->foreignUuid('reviewed_by')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->dateTime('reviewed_at')
                ->nullable();

            $table->text('doctor_note')
                ->nullable();

            $table->boolean('released_to_patient')
                ->default(false);

            $table->dateTime('released_at')
                ->nullable();

            $table->boolean('alert_sent_to_owner')
                ->default(false);

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('patient_id');
            $table->index('consultation_id');
            $table->index('result_type');
            $table->index('uploaded_by');
            $table->index('reviewed_by');
            $table->index('released_to_patient');
            $table->index('uploaded_at');
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
