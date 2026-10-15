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
        Schema::create('robco_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('secret_word');
            $table->integer('word_length')->default(6);
            $table->text('grid_data'); // JSON data for the memory dump
            $table->string('status')->default('waiting'); // waiting, playing, finished
            $table->string('player1_name')->default('Supervisor');
            $table->string('player2_name')->nullable();
            $table->string('player1_token');
            $table->string('player2_token')->nullable();
            $table->integer('player1_attempts')->default(4);
            $table->integer('player2_attempts')->default(4);
            $table->string('winner_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robco_rooms');
    }
};
