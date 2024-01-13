<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gerencia extends Model
{

    protected $fillable = [
        'id',
        'nombre',
        'conector',
    ];

    public function departamento()
    {
        return $this->hasMany(Departamento::class);
    }

}
