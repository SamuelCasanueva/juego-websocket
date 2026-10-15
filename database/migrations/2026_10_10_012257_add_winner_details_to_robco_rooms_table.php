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
        Schema::table('robco_rooms', function (Blueprint $table) {
            $table->string('winner_name')->nullable()->after('winner_token');
            $table->integer('attempts_used')->nullable()->after('winner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('robco_rooms', function (Blueprint $table) {
            $table->dropColumn(['winner_name', 'attempts_used']);
        });
    }
};
