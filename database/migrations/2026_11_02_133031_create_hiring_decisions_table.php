<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hiring_decisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->unique()
                ->constrained('applications')
                ->restrictOnDelete();

            $table->foreignId('decided_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('result', 30);

            $table->date('start_date')->nullable();

            $table->text('note')->nullable();

            $table->timestampTz('decided_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hiring_decisions');
    }
};