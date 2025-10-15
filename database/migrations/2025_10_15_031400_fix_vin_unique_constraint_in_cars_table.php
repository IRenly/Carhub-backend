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
        // Verificar si el índice cars_vin_unique existe antes de eliminarlo
        $indexExists = DB::select("
            SELECT COUNT(1) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = 'cars' 
            AND index_name = 'cars_vin_unique'
        ");

        if (!empty($indexExists) && $indexExists[0]->count > 0) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropUnique('cars_vin_unique');
            });
        }

        // Crear índice único que permite múltiples NULL o campos vacíos
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
            $table->unique('vin', 'cars_vin_unique');
        });
    }
};
