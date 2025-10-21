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
        Schema::create('banner' , function(Blueprint $table){
            $table->id();
            $table->string('enlace')->nullable();
            $table->string('video')->nullable();

            $table->integer('evento_id');
            $table->string('empresa', 45);

            
            $table->unique(['evento_id' , 'empresa']);//Evita duplicados , una empresa por evento.
            $table->index('empresa');//Acelera las busquedas y ordenaciones por empresa.
            $table->timestamps();

            $table->foreign('evento_id')->references('id')->on('evento')->cascadeOnDelete();

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
