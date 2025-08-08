<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->bigInteger('coche_id');
            $table->integer('parada_id');

            $table->enum('tipo', ['conductor', 'acompañante']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coche_id')->references('id')->on('coches')->onDelete('cascade');
            $table->foreign('parada_id')->references('id')->on('parada')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
