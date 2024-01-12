<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitosPuesto extends Model
{
    protected $table = 'requisitos_puestos';

    protected $fillable = [
        'id',
        'puesto_id',
        'requisito_id',
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }

    public function requisito()
    {
        return $this->belongsTo(Requisitos::class, 'requisito_id', 'id');
    }

}
