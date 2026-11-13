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
        Schema::create('staff_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('home_clinic_id')
                ->references('id')
                ->on('clinics')
                ->onDelete('cascade');

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone_number', 20);

            $table->string('email')->unique();

            $table->string('password_hash');

            $table->enum('role', [
                'owner',
                'head_receptionist',
                'receptionist',
                'doctor',
                'nurse',
                'pharmacist',
                'pharmacy_assistant',
                'accountant',
                'head_accountant',
                'lab_technician',
                'radiology_technician'
            ]);

            $table->string('specialization')->nullable();

            $table->boolean('is_active')->default(true);

            $table->dateTime('revoked_at')->nullable();

            $table->foreignUuid('revoked_by_user_id')
                ->nullable()
                ->references('id')
                ->on('staff_users')
                ->nullOnDelete();

            $table->dateTime('last_login_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('tenant_id');
            $table->index('last_name');
            $table->index('phone_number');
            $table->index('role');
            $table->index('home_clinic_id');
            $table->index('specialization');
            $table->index('is_active');
            $table->index('created_at');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_users');
    }
};
