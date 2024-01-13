<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    protected $fillable = [
        'id',
        'denominacion',
        'objetivo',
        'item',
        'estado',
        'salario',
        'salarioLiteral',
        'departamento_id',
        'persona_actual_id'
    ];

    public function personaPuesto()
    {
        return $this->hasMany(PersonaPuesto::class, 'puesto_id', 'id');
    }

    public function procesoDeIncorporacion()
    {
        return $this->hasMany(ProcesoDeIncorporacion::class);
    }

    public function requisitos_puesto()
    {
        return $this->hasMany(RequisitosPuesto::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function persona_actual()
    {
        return $this->belongsTo(Persona::class, 'persona_actual_id', 'id');
    }

    public function incorporacionFormulario()
    {
        return $this->hasMany(IncorporacionFormulario::class);
    }
}
