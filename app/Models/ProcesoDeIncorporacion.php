<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoDeIncorporacion extends Model
{
    protected $table = 'proceso_de_incorporaciones';

    protected $fillable = [
        'id',
        'propuestos',
        'estado',
        'remitente',
        'fechaAccion',
        'responsable',
        'informeCuadro',
        'fechaInformeCuadro',
        'hpHr',
        'sippase',
        'idioma',
        'fechaMovimiento',
        'tipoMovimiento',
        'itemOrigen',
        'cargoOrigen',
        'memorandum',
        'ra',
        'fechaMemorialRap',
        'sayri',
        'puesto_id',

    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }

}
