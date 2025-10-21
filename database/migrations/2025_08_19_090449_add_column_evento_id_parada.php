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
        Schema::table('parada', function (Blueprint $table) {
            $table->integer('evento_id')
                ->nullable()
                ->after('id');
        });

        Schema::table('parada', function (Blueprint $table) {
            $table->foreign('evento_id')
                ->references('id')
                ->on('evento')
                ->cascadeOnDelete() //Al borrar evento, borra paradas
                ->cascadeOnUpdate(); //Al cambiar PK , propaga

            $table->unique(['evento_id', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
