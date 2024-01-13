<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradoAcademico extends Model
{
    protected $table = 'grado_academicos';

    protected $fillable = [
        'id',
        'nombre',
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }
}