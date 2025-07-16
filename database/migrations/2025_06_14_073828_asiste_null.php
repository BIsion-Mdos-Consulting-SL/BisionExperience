<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coches', function (Blueprint $table) {
            $table->boolean('asiste')->default(false)->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('coches', function (Blueprint $table) {
            // Esto revierte el cambio si haces rollback
            $table->boolean('asiste')->nullable()->default(null)->change();
        });
    }
};
