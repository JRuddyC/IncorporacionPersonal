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
            $table->string('fileAc')->nullable();
            $table->date('fechaInicio')->nullable();
            $table->string('motivoBaja')->nullable();
            $table->date('fechaFin')->nullable();
            $table->tinyInteger('estado')->nullable();
            $table->unsignedBigInteger('creador_user_id')->nullable();
            $table->unsignedBigInteger('actualizador_user_id')->nullable();
            $table->foreign('creador_user_id')->references('id')->on('users')->nullable();
            $table->foreign('actualizador_user_id')->references('id')->on('users')->nullable();
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
