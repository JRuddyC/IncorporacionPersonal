<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'id',
        'ci',
        'exp',
        'primer_apellido',
        'segundo_apellido',
        'nombres',
        'nombre_completo',
        'formacion',
        'grado_academico_id',
        'area_formacion_id',
        'institucion_id',
        'sexo',
        'fecha_nacimiento',
        'telefono',
        'fecha_inicion_sin',
        'imagen',
    ];

    public function personaPuesto()
    {
        return $this->hasMany(PersonaPuesto::class);
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'persona_id');
    }

    public function incorporacionFormulario()
    {
        return $this->hasMany(IncorporacionFormulario::class);
    }

    public function puestos_actuales()
    {
        return $this->hasMany(Puesto::class, 'persona_actual_id', 'id');
    }

    public function gradoAcademico()
    {
        return $this->belongsTo(GradoAcademico::class, 'gradoAcademico_id', 'id');
    }

    public function areaFormacion()
    {
        return $this->belongsTo(AreaDeFormacion::class, 'areaFormacion_id', 'id');
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'institucion_id', 'id');
    }
}
