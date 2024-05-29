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
        Schema::create('user_category', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->decimal('meal_discount', 4, 2)->unsigned()->default(100.00); // Increase precision to 4
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_category');
    }
};
