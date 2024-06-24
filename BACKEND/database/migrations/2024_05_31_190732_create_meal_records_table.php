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
        Schema::create('meal_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('badge_id')->constrained('badges', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('meal_schedule_id')->constrained('meal_schedules', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamp('taken_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_records');
    }
};
