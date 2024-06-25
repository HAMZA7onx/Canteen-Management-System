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
        Schema::create('sunday_daily_meal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_meal_id')->constrained('daily_meals')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('week_schedule_id')->constrained('week_schedule')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sunday_daily_meal');
    }
};
