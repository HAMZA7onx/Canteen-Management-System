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
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            Schema::create("{$day}_records", function (Blueprint $table) use ($day) {
                $table->id();
                $table->foreignId("{$day}_daily_meal_id")
                    ->constrained("{$day}_daily_meal")
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->foreignId('badge_id')
                    ->constrained('badges')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($days as $day) {
            Schema::dropIfExists("{$day}_records");
        }
    }
};
