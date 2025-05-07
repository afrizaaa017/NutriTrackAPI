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
        Schema::create('daily_summaries', function (Blueprint $table) {
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->date('date');
            $table->primary(['email', 'date']);
            $table->integer('target_calories');
            $table->integer('calories_consumed');
            $table->integer('fat_consumed');
            $table->integer('sugar_consumed');
            $table->integer('carbs_consumed');
            $table->integer('protein_consumed');
            $table->float('weight_recap');
            $table->float('height_recap');
            $table->boolean('is_met')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_summaries');
    }
};
