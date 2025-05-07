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
        Schema::create('user_leaderboard', function (Blueprint $table) {
            $table->string('email')->unique();
            $table->primary('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->integer('streaks')->default(0);
            $table->date('last_streak_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_leaderboard');
    }
};
