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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('registration_clinic_id')
                ->references('id')
                ->on('clinics')
                ->onDelete('cascade');

            $table->string('national_id', 20);
            $table->string('first_name', 100);
            $table->string('last_name', 100);

            $table->date('date_of_birth');

            $table->enum('gender', [
                'male',
                'female',
            ]);

            $table->string('phone_number', 20);

            $table->string('email')->nullable();

            $table->text('address');

            $table->boolean('is_chronic')->default(false);

            $table->json('chronic_categories')->nullable();

            $table->boolean('portal_access_enabled')->default(false);

            $table->dateTime('registration_date');

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('registration_clinic_id');
            $table->index('national_id');
            $table->index('phone_number');
            $table->index('last_name');
            $table->index('is_chronic');

            // Unique Constraints
            $table->unique(['tenant_id', 'national_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};