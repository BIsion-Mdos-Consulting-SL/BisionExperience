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
        Schema::table('coches', function (Blueprint $table) {
            $table->boolean('seguro')->default(false)->nullable();
            $table->string('documento_seguro')->nullable();
            $table->string('foto_vehiculo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coches' , function (Blueprint $table) {
            $table->dropColumn(['seguro' , 'documento_seguro' , 'foto_vehiculo']);
        });
    }
};
