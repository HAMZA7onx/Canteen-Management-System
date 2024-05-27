<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table)
        {
            $table->id();
            $table->enum('name', ['breakfast', 'lunch', 'dinner', 'fotour', 'so7our'])->nullable();
            $table->enum('mode', ['normal', 'ramadan']);
            $table->integer('price')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->text('description');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
