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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('clinic_id')
                ->nullable()
                ->references('id')
                ->on('clinics')
                ->nullOnDelete();

            $table->string('recipient_type', 50);

            $table->uuid('recipient_id');

            $table->enum('channel', [
                'whatsapp',
                'sms',
                'email',
            ]);

            $table->string('notification_type', 100);

            $table->text('message_content');

            $table->enum('delivery_status', [
                'pending',
                'sent',
                'delivered',
                'failed',
            ])->default('pending');

            $table->text('failure_reason')
                ->nullable();

            $table->dateTime('sent_at');

            $table->dateTime('delivered_at')
                ->nullable();

            $table->timestamp('created_at')
                ->useCurrent();

            // Indexes
            $table->index('tenant_id');
            $table->index('clinic_id');

            $table->index('recipient_type');
            $table->index('recipient_id');
            $table->index(['recipient_type', 'recipient_id']);

            $table->index('channel');
            $table->index('notification_type');
            $table->index('delivery_status');

            $table->index('sent_at');
            $table->index('delivered_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};