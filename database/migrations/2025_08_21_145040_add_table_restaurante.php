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
        Schema::create('restaurante', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('foto_restaurante')->nullable();
            $table->string('enlace')->nullable();

            $table->integer('evento_id')->nullable();

            $table->foreign('evento_id')
                ->references('id')
                ->on('evento')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
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
