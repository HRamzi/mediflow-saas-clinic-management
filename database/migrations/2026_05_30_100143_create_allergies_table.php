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
        Schema::create('allergies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->string('allergen_name');

            $table->enum('severity', [
                'mild',
                'moderate',
                'severe',
                'anaphylactic',
            ]);

            $table->text('reaction_description')->nullable();

            $table->foreignUuid('recorded_by_doctor_id')
                ->references('id')
                ->on('staff_users')
                ->onDelete('cascade');

            $table->dateTime('recorded_at');

            $table->boolean('is_active')->default(true);

            // Indexes
            $table->index('tenant_id');
            $table->index('patient_id');
            $table->index('allergen_name');
            $table->index('severity');
            $table->index('recorded_by_doctor_id');
            $table->index('recorded_at');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergies');
    }
};