<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Intentar eliminar el índice único anterior si existe
        Schema::table('cars', function (Blueprint $table) {
            try {
                $table->dropUnique(['vin']);
            } catch (\Throwable $e) {
                // Ignorar si el índice no existía
            }
        });

        // Crear índice único que ignora cadenas vacías (solo en MySQL 8+)
        DB::statement('CREATE UNIQUE INDEX cars_vin_unique_filtered ON cars ((NULLIF(vin, "")))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('DROP INDEX cars_vin_unique_filtered ON cars');
        } catch (\Throwable $e) {
            // Ignorar si no existe
        }

        Schema::table('cars', function (Blueprint $table) {
            $table->unique('vin');
        });
    }
};
