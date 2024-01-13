<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puesto;
use Illuminate\Support\Facades\DB;

class PersonaPuestoController extends Controller
{
    public function listarPuesto(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        //Filtros
        $item = $request->input('item');
        $gerenciasIds = $request->input('gerenciasIds');
        $departamentosIds = $request->input('departamentosIds');
        $estado = $request->input('estado');
        $tipoMovimiento = $request->input('tipoMovimiento');

        $query = DB::table('puestos')
            ->join('departamentos', 'puestos.departamento_id', '=', 'departamentos.id')
            ->join('gerencias', 'departamentos.gerencia_id', '=', 'gerencias.id')
            ->leftJoin('personas', 'personas.id', '=', 'puestos.persona_actual_id');

        if (isset($item)) {
            $query = $query->where('puestos.item', $item);
        }
        if (isset($departamentosIds) && count($departamentosIds) > 0) {
            $query = $query->whereIn('departamentos.id', $departamentosIds);
        }
        if (isset($gerenciasIds) && count($gerenciasIds) > 0) {
            $query = $query->whereIn('departamentos.gerencia_id', $gerenciasIds);
        }
        if (isset($estado)) {
            $query = $query->where('puestos.estado', $estado);
        }

        $query = $query->select([
            'puestos.id',
            'puestos.denominacion',
            'puestos.item',
            'puestos.estado',
            'puestos.persona_actual_id',
            'personas.nombreCompleto',
            'personas.imagen',
            'personas.ci',
            'personas.exp',
            'personas.formacion',
            'departamentos.nombre as departamento',
            'gerencias.nombre as gerencia'
        ]);

        // Paginacion de personas puestos
        $personaPuestos = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json($personaPuestos);
    }

    public function obtenerInfoDePersonapuesto($puestoId)
    {
        $personaPuesto = Puesto::with(['persona_actual', 'departamento.gerencia', 'requisitos_puesto.requisito'])->find($puestoId);

        return response()->json($personaPuesto);
    }

    public function filtrarAutoComplete(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $result = DB::table('puestos')
            ->leftJoin('personas', 'personas.id', '=', 'puestos.persona_actual_id')
            ->orWhere(DB::raw('CAST(puestos.item AS CHAR)'), 'LIKE', $keyword . "%")
            ->orWhere('personas.nombreCompleto', 'LIKE', $keyword . "%")
            ->select(['puestos.item as item', 'personas.nombreCompleto as nombreCompleto'])
            ->limit(6)->get();
        $results = [];
        if (ctype_digit($keyword)) {
            $results = $result->map(function ($obj) {
                return (object) ['text' => "" . $obj->item . ": " . ($obj->nombreCompleto ? $obj->nombreCompleto : "ACEFALIA"), 'item' => $obj->item];
            });
        } else {
            $results = $result->map(function ($obj) {
                return (object) ['text' => $obj->nombreCompleto . " [" . $obj->item . "]", 'item' => $obj->item];
            });
        }
        return response()->json(['elementos' => $results], 200);
    }
}