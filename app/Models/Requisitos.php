<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisitos extends Model
{
    use HasFactory;

    protected $table = 'requisitos';

    protected $fillable = [
        'id',
        'formacionRequerida',
        'experienciaProfesionalSegunCargo',
        'experienciaRelacionadoAlArea',
        'experienciaEnFuncionesDeMando'
    ];

    public function requisitosPuesto()
    {
        return $this->hasMany(RequisitosPuesto::class);
    }

}
