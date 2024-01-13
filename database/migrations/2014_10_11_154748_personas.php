<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->unique();
            $table->string('exp')->nullable();
            $table->string('nombres');
            $table->string('primerApellido');
            $table->string('segundoApellido')->nullable();
            $table->string('nombreCompleto');
            $table->string('formacion')->nullable();
            $table->string('sexo');
            $table->date('fechaNacimiento')->nullable();
            $table->string('telefono')->nullable();
            $table->string('imagen')->nullable();
            $table->unsignedBigInteger('gradoAcademico_id')->nullable();
            $table->unsignedBigInteger('areaFormacion_id')->nullable();
            $table->unsignedBigInteger('institucion_id')->nullable();
            $table->year('anioConclusion')->nullable();
            $table->timestamps();
            $table->foreign('gradoAcademico_id')->references('id')->on('grado_academicos');
            $table->foreign('areaFormacion_id')->references('id')->on('area_formacions');
            $table->foreign('institucion_id')->references('id')->on('instituciones');
            $table->date('fechaInicioEnSin')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas');
    }
};
