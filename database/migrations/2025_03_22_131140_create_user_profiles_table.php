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
            $table->enum('goal', ['gain a little weight', 'gain a lot of weight', 'lose a little weight', 'lose a lot of weight', 'maintain']);
            $table->enum('AMR', ['sedentary active', 'lightly active', 'moderately active', 'highly active']);
            $table->float('calories_needed');
            $table->boolean('gender');
            $table->string('image')->nullable();
            $table->integer('points')->default(0);

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
