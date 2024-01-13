<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaDeFormacion extends Model
{
    protected $table = 'area_formacions';

    protected $fillable = [
        'id',
        'nombre',
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }

}
