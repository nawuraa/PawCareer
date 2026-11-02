<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('division_id')
                ->constrained('divisions')
                ->restrictOnDelete();

            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['division_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};