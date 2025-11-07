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
        Schema::table('evento_conductor' , function(Blueprint $table) {
            $table->string('cif')->nullable()->after('conductor_id');
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('empresa')->nullable();
            $table->enum('vehiculo_prop' , ['si' , 'no'])->nullable();
            $table->enum('vehiculo_emp' , ['si' , 'no'])->nullable();
            $table->string('intolerancia')->nullable();
            $table->string('preferencia')->nullable();
            $table->string('carnet')->nullable();
            $table->string('etiqueta')->nullable();
            $table->string('kam')->nullable();
            $table->boolean('asiste')->default(false);
            $table->string('dni')->nullable();
            $table->boolean('proteccion_datos')->default(false);
            $table->date('carnet_caducidad')->nullable();
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
