<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();

            $table->string('subject_type', 100);
            $table->unsignedBigInteger('subject_id');

            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);

            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('reason')->nullable();

            $table->timestampTz('created_at')->useCurrent();

            $table->index(
                ['subject_type', 'subject_id'],
                'status_histories_subject_index'
            );

            $table->index('changed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};