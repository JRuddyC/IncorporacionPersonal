<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportExcelData;
use App\Models\ImportLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Validators\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class ImportarExcelController extends Controller
{
    public function importExcel(Request $request)
    {
        try {
            $userAuth = Auth::user();
            $file = $request->file('archivoPlanilla');
            Excel::import(new ImportExcelData, $file);
            ImportLog::create([
                'usuario_id' => $userAuth->id,
            ]);
            Alert::success('Importación completada', 'La importación se ha realizado con éxito.')->persistent(true, true);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Fila {$failure->row()}: {$failure->errors()[0]}";
            }
            Alert::error('Error en la importación', implode('<br>', $errorMessages))->persistent(true, true);
        } catch (\Exception $e) {
            dd($e);
            Alert::error('Error en la importación', 'Se ha producido un error durante la importación.')->persistent(true, true);
        }

        return redirect()->back();
    }
}
