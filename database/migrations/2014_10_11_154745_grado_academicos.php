<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('grado_academicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        DB::table('grado_academicos')->insert([
            ['nombre' => 'Bachiller'],
            ['nombre' => 'Egresado'],
            ['nombre' => 'Estudiante Universitario'],
            ['nombre' => 'Licenciatura'],
            ['nombre' => 'Tecnico Medio'],
            ['nombre' => 'Tecnico Superior'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('grado_academicos');
    }
};
