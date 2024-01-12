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
        'an',
        'exp',
        'nombres',
        'primerApellido',
        'segundoApellido',
        'nombreCompleto',
        'formacion',
        'sexo',
        'fechaNacimiento',
        'telefono',
        'imagen',
        'gradoAcademico_id',
        'areaFormacion_id',
        'universidad_id',
        'anoConclusion',
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

    public function universidad()
    {
        return $this->belongsTo(Universidad::class, 'universidad_id', 'id');
    }
}
