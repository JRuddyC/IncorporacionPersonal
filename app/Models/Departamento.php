<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'id',
        'nombre',
        'conector',
        'gerencia_id'
    ];

    public function puesto()
    {
        return $this->hasMany(Puesto::class);
    }

    public function gerencia() {
        return $this->belongsTo(Gerencia::class);
    }

}


