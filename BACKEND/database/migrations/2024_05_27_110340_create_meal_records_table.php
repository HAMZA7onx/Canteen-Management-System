<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('badge_id')->constrained('badges', 'id');
            $table->foreignId('meal_id')->constrained('meals', 'id');
            $table->timestamp('taken_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_records');
    }
};
