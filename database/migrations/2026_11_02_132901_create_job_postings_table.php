<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('requisition_id')
                ->unique()
                ->constrained('requisitions')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('title', 150);
            $table->text('description');
            $table->text('requirements')->nullable();

            $table->unsignedSmallInteger('quota');
            $table->date('deadline');

            $table->string('status', 30)->default('open');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("
            ALTER TABLE job_postings
            ADD CONSTRAINT job_postings_quota_check
            CHECK (quota BETWEEN 1 AND 50)
        ");

        DB::statement("
            ALTER TABLE job_postings
            ADD CONSTRAINT job_postings_status_check
            CHECK (status IN ('draft', 'open', 'closed'))
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};