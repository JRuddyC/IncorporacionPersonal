<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('requisitos_puestos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('puesto_id')->nullable();
            $table->unsignedBigInteger('requisito_id')->nullable();
            $table->foreign('puesto_id')->references('id')->on('puestos');
            $table->foreign('requisito_id')->references('id')->on('requisitos');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requisitos_puestos');
    }
};