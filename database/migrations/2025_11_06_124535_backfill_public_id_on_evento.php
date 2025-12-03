<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Asegura la columna (por si en algún entorno falta)
        if (!Schema::hasColumn('evento', 'public_id')) {
            Schema::table('evento', function ($table) {
                // 36 para UUID v4; para ULID usa 26
                $table->string('public_id', 36)->nullable()->after('id');
            });
        }

        // 2) Rellena sólo los NULL o vacíos
        DB::statement("UPDATE evento SET public_id = UUID() WHERE public_id IS NULL OR public_id = ''");

        // 3) Índices sobre public_id, PERO solo si no existen ya 👇
        // ------------------------------------------------------
        $hasUnique = !empty(DB::select("SHOW INDEX FROM evento WHERE Key_name = 'evento_public_id_unique'"));

        $hasIndex = !empty(DB::select("SHOW INDEX FROM evento WHERE Key_name = 'evento_public_id_index'"));

        Schema::table('evento', function ($table) use ($hasUnique, $hasIndex) {
            if (!$hasUnique) {
                $table->unique('public_id', 'evento_public_id_unique');
            }

            if (!$hasIndex) {
                $table->index('public_id', 'evento_public_id_index');
            }
        });
    }
    public function down(): void
    {
        // No deshacemos el backfill (podrías quitar los índices si quieres)
        Schema::table('evento', function ($table) {
            // Comentado para no perder datos accidentalmente
            // $table->dropUnique('evento_public_id_unique');
            // $table->dropIndex('evento_public_id_index');
            // $table->dropColumn('public_id');
        });
    }
};
