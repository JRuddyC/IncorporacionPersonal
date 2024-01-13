<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('personas_puestos', function (Blueprint $table) {
            $table->id();
            $table->string('estadoFormacion')->nullable();
            $table->string('formacion')->nullable();
            $table->string('fileAc')->nullable();
            $table->date('fechaInicioEnSin')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->string('nombreCompletoDesvinculacion')->nullable();
            $table->string('motivoBaja')->nullable();
            $table->date('fechaFin')->nullable();
            $table->string('estado')->nullable();
            $table->unsignedBigInteger('puesto_id')->nullable();
            $table->unsignedBigInteger('persona_id')->nullable();
            $table->foreign('puesto_id')->references('id')->on('puestos');
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas_puestos');
    }
};