<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la columna de configuración personalizada a las salas RobCo.
     */
    public function up(): void
    {
        Schema::table('robco_rooms', function (Blueprint $table) {
            $table->json('settings')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('robco_rooms', function (Blueprint $table) {
            $table->dropColumn('settings');
        });
    }
};
