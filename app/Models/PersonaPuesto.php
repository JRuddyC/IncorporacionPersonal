<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaPuesto extends Model
{
    protected $table = 'personas_puestos';

    protected $fillable = [
        'id',
        'estadoFormacion',
        'formacion',
        'fileAc',
        'fechaInicioEnSin',
        'fechaInicio',
        'nombreCompletoDesvinculacion',
        'motivoBaja',
        'fechaFin',
        'estado',
        'puesto_id',
        'persona_id',
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

}
