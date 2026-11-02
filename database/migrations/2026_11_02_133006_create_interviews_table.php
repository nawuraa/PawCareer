<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained('applications')
                ->restrictOnDelete();

            $table->foreignId('interviewer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestampTz('scheduled_at');

            $table->string('status', 30)->default('scheduled');

            $table->unsignedSmallInteger('score')->nullable();

            $table->text('evaluation_notes')->nullable();

            $table->timestampTz('completed_at')->nullable();

            $table->timestamps();
        });

        DB::statement("
            ALTER TABLE interviews
            ADD CONSTRAINT interviews_score_check
            CHECK (score IS NULL OR score BETWEEN 0 AND 100)
        ");

        DB::statement("
            ALTER TABLE interviews
            ADD CONSTRAINT interviews_status_check
            CHECK (
                status IN (
                    'scheduled',
                    'completed',
                    'cancelled',
                    'no_show'
                )
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};