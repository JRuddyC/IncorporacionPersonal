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
        } catch (ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Fila {$failure->row()}: {$failure->errors()[0]}";
            }
            return response()->json(["msn"=>"error"],406);
        } catch (\Exception $e) {
            // dd($e);
            return response()->json(["msn"=>"error"],406);
        }

        return response()->json(["msn"=>"exitoso"],200);
    }
}
