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
        Schema::create('menu_component', function (Blueprint $table) {
            $table->foreignId('meal_menu_id')->constrained('meal_menus', 'id');
            $table->foreignId('component_id')->constrained('components', 'id');
            $table->primary(['meal_menu_id', 'component_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_component');
    }
};
