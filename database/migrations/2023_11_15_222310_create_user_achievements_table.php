<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->tinyInteger('achievement_id_comment')->nullable();
            $table->bigInteger('comment_id')->nullable();
            $table->tinyInteger('achievement_id_lesson')->nullable();
            $table->bigInteger('lesson_id')->nullable();
            $table->tinyInteger('badge_id')->nullable();
            $table->integer('total_achievements')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
