<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    protected $table = "universidads";

    protected $fillable = [
        'id',
        'nombre',
    ];

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }

}
