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
        Schema::create('meal_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_menu_id')->constrained('meal_menus', 'id');
            $table->string('component_name');
            $table->text('description')->nullable();
            $table->decimal('base_price', 8, 2)->unsigned();
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_components');
    }
};
