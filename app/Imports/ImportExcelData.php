<?php
namespace App\Imports;

use App\Models\Departamento;
use App\Models\Gerencia;
use App\Models\Persona;
use App\Models\PersonaPuesto;
use App\Models\ProcesoDeIncorporacion;
use App\Models\Puesto;
use App\Models\RequisitosPuesto;
use App\Models\Requisitos;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class ImportExcelData implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $gerencia = $this->migrateGerencia($row[1]);

        $departamento = $this->migrarDepartamento($row[2], $gerencia->id);

        $puesto = $this->migrarPuesto($row[0], $row[3], $row[4], $row[5], $row[42], $departamento->id);

        if (isset($row[6]) && isset($row[11])) {

            $persona = $this->migrarPersona($row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[11] . ' ' . $row[9] . ' ' . $row[10], $row[14], $row[15], $row[16], $row[18]);
            $puesto->persona_actual_id = $persona->id;
            $puesto->estado = 'OCUPADO';
            $puesto->save();
            $personaPuesto = $this->migrarPersonaPuesto($row[13], $row[14], $row[0] . ' ' . $row[6], $row[19], $row[20], $row[39], $row[40], $row[41], $row[9], $puesto->id, $persona->id);
        }

        $procesoDeIncorporacion = $this->migrarProcesoDeIncorporacion($row[21], $row[22], $row[23], $row[24], $row[25], $row[26], $row[27], $row[28], $row[29], $row[30], $row[31], $row[32], $row[33], $row[34], $row[35], $row[36], $row[37], $row[38], $puesto->id);

        $requisitos = $this->migrarRequisitos($row[43], $row[44], $row[45], $row[46]);

        $requisitoPuesto = $this->migrarRequisitosPuesto($puesto->id, $requisitos->id);

    }

    public function migrateGerencia($nombre): Gerencia
    {
        $gerencia = Gerencia::where('nombre', $nombre)->first();
        if (!isset($gerencia)) {
            $gerencia = Gerencia::create([
                'nombre' => $nombre
            ]);
        }
        return $gerencia;
    }

    public function migrarDepartamento($nombre, $gerenciaId): Departamento
    {
        $departamento = Departamento::where('nombre', $nombre)->where('gerencia_id', $gerenciaId)->first();
        if (!isset($departamento)) {
            $departamento = Departamento::create([
                'nombre' => $nombre,
                'gerencia_id' => $gerenciaId
            ]);
        }
        return $departamento;
    }

    public function migrarPersona($ci, $an, $exp, $primerApellido, $segundoApellido, $nombres, $nombreCompleto, $formacion, $sexo, $fechaNacimiento, $telefono): Persona
    {
        $persona = Persona::where('ci', $ci)->first();
        if (!isset($persona)) {
            $timestamp = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fechaNacimiento);
            $fechaNacimiento = Carbon::createFromTimestamp($timestamp)->format('Y-m-d');
            $persona = Persona::create([
                'ci' => $ci,
                'an' => $an,
                'exp' => $exp,
                'primerApellido' => $primerApellido,
                'segundoApellido' => $segundoApellido,
                'nombres' => $nombres,
                'nombreCompleto' => $nombres . ' ' . $primerApellido . ' ' . $segundoApellido,
                'formacion' => $formacion,
                'sexo' => $sexo,
                'fechaNacimiento' => $fechaNacimiento,
                'telefono' => $telefono
            ]);
        }
        return $persona;
    }

    public function migrarPuesto($item, $denominacion, $salario, $salarioLiteral, $objetivo, $departamentoId): Puesto
    {
        $puesto = Puesto::where('item', $item)->first();
        if (!isset($puesto)) {
            $puesto = Puesto::create([
                'item' => $item,
                'denominacion' => $denominacion,
                'salario' => $salario,
                'salarioLiteral' => $salarioLiteral,
                'objetivo' => $objetivo,
                'departamento_id' => $departamentoId,
                'estado' => 'ACEFALIA',
            ]);
        } else {
            $puesto->denominacion = $denominacion;
            $puesto->salario = $salario;
            $puesto->salarioLiteral = $salarioLiteral;
            $puesto->objetivo = $objetivo;
            $puesto->departamento_id = $departamentoId;
            $puesto->estado = 'ACEFALIA';
            $puesto->persona_actual_id = null;
            $puesto->save();
        }
        return $puesto;
    }

    private function convertirFechaATimestamp($fecha)
    {
        try {
            $carbonDate = Carbon::createFromFormat('d/m/Y', $fecha);

            if ($carbonDate instanceof Carbon) {
                return $carbonDate->getTimestamp();
            }
        } catch (\Exception $e) {
            error_log("Error al convertir fecha: " . $e->getMessage());
        }

        try {
            $excelDate = intval($fecha);
            $carbonDate = Carbon::createFromTimestamp(($excelDate - 25569) * 86400);

            if ($carbonDate instanceof Carbon) {
                return $carbonDate->getTimestamp();
            }
        } catch (\Exception $e) {
            error_log("Error al convertir número de serie de Excel: " . $e->getMessage());
        }

        error_log("No se pudo convertir la fecha: $fecha");
        return 0;
    }

    public function migrarPersonaPuesto($estadoFormacion, $formacion, $fileAc, $fechaInicioEnSin, $fechaInicio, $nombreCompletoDesvinculacion, $motivoBaja, $fechaFin, $estado, $puestoId, $personaId): PersonaPuesto
    {
        $persona = Persona::find($personaId);
        $puesto = Puesto::find($puestoId);

        if (!$persona || !$puesto) {
            return null;
        }

        $fileAc = $puesto->item . '-' . $persona->ci;

        if ($estado === 'ACEFALIA') {
            $estado = 'Desocupado';
        } else {
            $estado = 'Ocupado';
        }

        $personaPuesto = PersonaPuesto::where('estadoFormacion', $estadoFormacion)
            ->where('puesto_id', $puestoId)
            ->where('persona_id', $personaId)
            ->first();

        if (!isset($personaPuesto)) {
            error_log("FechaInicioEnSin original: $fechaInicioEnSin");
            error_log("FechaInicio original: $fechaInicio");
            error_log("FechaFin original: $fechaFin");

            $timestampFechaInicioEnSin = $this->convertirFechaATimestamp($fechaInicioEnSin);
            $fechaInicioEnSin = Carbon::createFromTimestamp($timestampFechaInicioEnSin)->format('Y-m-d');

            $timestampFechaInicio = $this->convertirFechaATimestamp($fechaInicio);
            $fechaInicio = Carbon::createFromTimestamp($timestampFechaInicio)->format('Y-m-d');

            $timestampFechaFin = $this->convertirFechaATimestamp($fechaFin);
            $fechaFin = Carbon::createFromTimestamp($timestampFechaFin)->format('Y-m-d');

            error_log("FechaInicioEnSin convertida: $fechaInicioEnSin");
            error_log("FechaInicio convertida: $fechaInicio");
            error_log("FechaFin convertida: $fechaFin");

            $personaPuesto = PersonaPuesto::create([
                'estadoFormacion' => $estadoFormacion,
                'formacion' => $formacion,
                'fileAc' => $fileAc,
                'fechaInicioEnSin' => $fechaInicioEnSin,
                'fechaInicio' => $fechaInicio,
                'nombreCompletoDesvinculacion' => $nombreCompletoDesvinculacion,
                'motivoBaja' => $motivoBaja,
                'fechaFin' => $fechaFin,
                'estado' => $estado,
                'puesto_id' => $puestoId,
                'persona_id' => $personaId
            ]);
        }
        return $personaPuesto;
    }

    public function migrarProcesoDeIncorporacion($propuestos, $estado, $remitente, $fechaAccion, $responsable, $informeCuadro, $fechaInformeCuadro, $hpHr, $sippase, $idioma, $fechaMovimiento, $tipoMovimiento, $itemOrigen, $cargoOrigen, $memorandum, $ra, $fechaMermorialRap, $sayri, $puestoId): ProcesoDeIncorporacion
    {
        $procesoDeIncorporacion = ProcesoDeIncorporacion::where('propuestos', $propuestos)->where('puesto_id', $puestoId)->first();

        if (!isset($procesoDeIncorporacion)) {
            if (!empty($fechaAccion)) {
                $timestampFechaAccion = $this->convertirFechaATimestamp($fechaAccion);
                $fechaAccion = Carbon::createFromTimestamp($timestampFechaAccion)->format('Y-m-d');
            }

            if (!empty($fechaInformeCuadro)) {
                $timestampFechaInformeCuadro = $this->convertirFechaATimestamp($fechaInformeCuadro);
                $fechaInformeCuadro = Carbon::createFromTimestamp($timestampFechaInformeCuadro)->format('Y-m-d');
            }

            if (!empty($fechaMovimiento)) {
                $timestampFechaMovimiento = $this->convertirFechaATimestamp($fechaMovimiento);
                $fechaMovimiento = Carbon::createFromTimestamp($timestampFechaMovimiento)->format('Y-m-d');
            }

            if (!empty($fechaMermorialRap)) {
                $timestampFechaMermorialRap = $this->convertirFechaATimestamp($fechaMermorialRap);
                $fechaMermorialRap = Carbon::createFromTimestamp($timestampFechaMermorialRap)->format('Y-m-d');
            }

            $procesoDeIncorporacion = ProcesoDeIncorporacion::create([
                'propuestos' => $propuestos,
                'estado' => $estado,
                'remitente' => $remitente,
                'fechaAccion' => $fechaAccion,
                'responsable' => $responsable,
                'informeCuadro' => $informeCuadro,
                'fechaInformeCuadro' => $fechaInformeCuadro,
                'hpHr' => $hpHr,
                'sippase' => $sippase,
                'idioma' => $idioma,
                'fechaMovimiento' => $fechaMovimiento,
                'tipoMovimiento' => $tipoMovimiento,
                'itemOrigen' => $itemOrigen,
                'cargoOrigen' => $cargoOrigen,
                'memorandum' => $memorandum,
                'ra' => $ra,
                'fechaMermorialRap' => $fechaMermorialRap,
                'sayri' => $sayri,
                'puesto_id' => $puestoId,
            ]);
        }

        return $procesoDeIncorporacion;
    }

    public function migrarRequisitos($formacionRequerida, $experienciaProfesionalSegunCargo, $experienciaRelacionadoAlArea, $experienciaEnFuncionesDeMando): Requisitos
    {
        $requisitos = Requisitos::where('formacionRequerida', $formacionRequerida)->first();
        if (!isset($requisitos)) {
            $requisitos = Requisitos::create([
                'formacionRequerida' => $formacionRequerida,
                'experienciaProfesionalSegunCargo' => $experienciaProfesionalSegunCargo,
                'experienciaRelacionadoAlArea' => $experienciaRelacionadoAlArea,
                'experienciaEnFuncionesDeMando' => $experienciaEnFuncionesDeMando
            ]);
        }
        return $requisitos;
    }

    public function migrarRequisitosPuesto($puestoId, $requisitoId): RequisitosPuesto
    {
        $requisitoPuesto = RequisitosPuesto::where('puesto_id', $puestoId)->first();
        if (!isset($requisitoPuesto)) {
            $requisitoPuesto = RequisitosPuesto::create([
                'puesto_id' => $puestoId,
                'requisito_id' => $requisitoId
            ]);
        } else {
            $requisitoPuesto->requisito_id = $requisitoId;
            $requisitoPuesto->save();
        }
        return $requisitoPuesto;
    }
}
