<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->unsignedSmallInteger('quantity');
            $table->text('reason');

            $table->string('status', 30)->default('submitted');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('review_note')->nullable();
            $table->timestampTz('reviewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("
            ALTER TABLE requisitions
            ADD CONSTRAINT requisitions_quantity_check
            CHECK (quantity BETWEEN 1 AND 50)
        ");

        DB::statement("
            ALTER TABLE requisitions
            ADD CONSTRAINT requisitions_status_check
            CHECK (status IN ('submitted', 'approved', 'rejected'))
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};