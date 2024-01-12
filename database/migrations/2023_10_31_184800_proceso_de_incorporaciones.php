<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('proceso_de_incorporaciones', function (Blueprint $table) {
            $table->id();
            $table->string('propuestos')->nullable();
            $table->string('estado')->nullable();
            $table->string('remitente')->nullable();
            $table->date('fechaAccion')->nullable();
            $table->string('responsable')->nullable();
            $table->string('informeCuadro')->nullable();
            $table->date('fechaInformeCuadro')->nullable();
            $table->string('hpHr')->nullable();
            $table->string('sippase')->nullable();
            $table->string('idioma')->nullable();
            $table->date('fechaMovimiento')->nullable();
            $table->string('tipoMovimiento')->nullable();
            $table->string('itemOrigen')->nullable();
            $table->string('cargoOrigen')->nullable();
            $table->string('memorandum')->nullable();
            $table->string('ra')->nullable();
            $table->date('fechaMemorialRap')->nullable();
            $table->string('sayri')->nullable();
            $table->unsignedBigInteger('puesto_id');
            $table->foreign('puesto_id')->references('id')->on('puestos');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proceso_de_incorporaciones');
    }
};
