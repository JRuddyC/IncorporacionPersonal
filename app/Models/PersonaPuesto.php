<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaPuesto extends Model
{
    protected $table = 'personas_puestos';

    protected $fillable = [
        'id',
        'estadoFormacion',
        'fileAc',
        'fechaInicio',
        'motivoBaja',
        'fechaFin',
        'estado',
        'puesto_id',
        'persona_id',
        'creador_user_id',
        'actualizador_user_id'
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
