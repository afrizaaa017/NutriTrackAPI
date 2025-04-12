<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consumes', function (Blueprint $table) {
            $table->string('email');
            $table->integer('food_id');
            // $table->primary(['email', 'food_id']);
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->foreign('food_id')->references('food_id')->on('foods')->onDelete('cascade');
            $table->enum('meal_time', ['breakfast', 'lunch', 'dinner', 'snack']); 
            $table->integer('portion');
            $table->float('total_sugar');
            $table->float('total_calories');
            $table->float('total_fat');
            $table->float('total_carbs');
            $table->float('total_protein');
            $table->timestamp('consumed_at'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumes');
    }
};
