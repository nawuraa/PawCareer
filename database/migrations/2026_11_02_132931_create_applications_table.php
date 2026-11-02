<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_posting_id')
                ->constrained('job_postings')
                ->restrictOnDelete();

            $table->foreignId('applicant_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('cv_path', 255);

            $table->string('status', 30)->default('submitted');

            $table->timestampTz('submitted_at');

            $table->timestamps();

            $table->unique(
                ['job_posting_id', 'applicant_id'],
                'applications_job_applicant_unique'
            );
        });

        DB::statement("
            ALTER TABLE applications
            ADD CONSTRAINT applications_status_check
            CHECK (
                status IN (
                    'submitted',
                    'screening_passed',
                    'screening_failed',
                    'interview',
                    'accepted',
                    'rejected',
                    'cancelled'
                )
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};