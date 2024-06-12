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
        Schema::create('category_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('user_category', 'id');
            $table->foreignId('meal_schedule_id')->constrained('meal_schedules', 'id');
            $table->decimal('meal_discount')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_discounts');
    }
};
