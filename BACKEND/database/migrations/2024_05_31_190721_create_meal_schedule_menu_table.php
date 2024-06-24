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
        Schema::create('meal_schedule_menu', function (Blueprint $table) {
            $table->foreignId('meal_schedule_id')->constrained('meal_schedules')->cascadeOnDelete();
            $table->foreignId('meal_menu_id')->constrained('meal_menus')->cascadeOnDelete();
            $table->primary(['meal_schedule_id', 'meal_menu_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_schedule_menu');
    }
};