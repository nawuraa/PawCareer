<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('type', 100);

            $table->jsonb('data');

            $table->timestampTz('read_at')->nullable();

            $table->timestampTz('created_at')->useCurrent();

            $table->index(
                ['user_id', 'read_at'],
                'application_notifications_user_read_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_notifications');
    }
};