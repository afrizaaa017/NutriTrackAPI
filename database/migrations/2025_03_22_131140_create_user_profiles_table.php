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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->string('email')->unique();
            $table->primary('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->float('weight');
            $table->float('height');
            $table->date('birthday');
            $table->enum('goal', ['Gain a little weight', 'Gain a lot of weight', 'Lose a little weight', 'Lose a lot of weight', 'Maintain weight']);
            $table->enum('AMR', ['Sedentary active', 'Lightly active', 'Moderately active', 'Highly active', 'Extremely active']);
            $table->float('calories_needed');
            $table->boolean('gender');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
