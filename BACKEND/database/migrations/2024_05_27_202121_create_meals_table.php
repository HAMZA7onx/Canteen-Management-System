<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_name_id')->constrained('meal_names', 'id');
            $table->foreignId('meal_mode_id')->constrained('meal_modes', 'id');
            $table->foreignId('meal_items_id')->constrained('meal_items', 'id');
            $table->decimal('price', 8, 2)->unsigned();
            $table->integer('meals_number')->unsigned();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
