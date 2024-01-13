<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ImportarExcelController;
use App\Http\Controllers\IncorporacionesController;
use App\Http\Controllers\PersonaPuestoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','verified'])->group(function(){
    Route::apiResource('/user', UserController::class);
    Route::apiResource('/role', RoleController::class);
    Route::apiResource('/permissions', PermissionController::class);
});

// importar por api
Route::post('/planilla', [ImportarExcelController::class, 'importExcel'])->name('planilla');


Route::post('/persona-puesto/listar', [PersonaPuestoController::class, 'listarPuesto'])->name('importaciones.buscar');
Route::post('/persona-puesto/filtrar', [PersonaPuestoController::class, 'filtrarAutoComplete'])->name('importaciones.filtrar-autocomplete');
Route::get('/persona-puesto/{personaPuestoId}', [PersonaPuestoController::class, 'obtenerInfoDePersonapuesto'])->name('persona.puest.byid');
// Incorporaciones
Route::post('/incorporaciones/listar', [IncorporacionesController::class, 'listarIncorporaciones'])->name('incorporaciones-listar');
Route::post('/incorporaciones/filtrar', [IncorporacionesController::class, 'filtrarAutoComplete'])->name('incorporaciones-filtrar');
Route::get('/incorporaciones/{item}/buscar-item', [IncorporacionesController::class,'buscarItemApi'])->name('buscar-item');
Route::post('/incorporaciones/buscar-persona', [IncorporacionesController::class,'buscarPersona'])->name('buscar-persona');
Route::post('/incorporaciones/create-evaluacion', [IncorporacionesController::class,'crearEvaluacion'])->name('eval.crear');
Route::post('/incorporaciones/create-incorporacion', [IncorporacionesController::class,'crearIncorporacion'])->name('incorp.crear');
Route::post('/incorporaciones/create-gradoAcademico', [IncorporacionesController::class,'crearGradoAcademico'])->name('eval.crearGradoAcademico');
Route::post('/incorporaciones/create-areaFormacion', [IncorporacionesController::class,'crearAreaFormacion'])->name('eval.crearAreaFormacion');
Route::post('/incorporaciones/create-institucion', [IncorporacionesController::class,'crearInstitucion'])->name('eval.crearInstitucion');
Route::post('/incorporaciones/{incorporacionId}/observacion/{calificacion}', [IncorporacionesController::class,'observacion'])->name('eval.observacion');
Route::post('/incorporaciones/{incorporacionId}/eval-finalizar', [IncorporacionesController::class,'evaluacionFinalizar'])->name('eval.finalizar');
Route::post('/incorporaciones/{incorporacionId}/inc-actualizar', [IncorporacionesController::class,'incActualizar'])->name('inc.actualizar');
//links de formularios
//durante la evaluacion
Route::post('/incorporaciones/{incorporacionId}/gen-form-evaluacion', [IncorporacionesController::class,'generarFormularioEvalucaion'])->name('eval.gen-form-evaluacion');
Route::post('/incorporaciones/{incorporacionId}/gen-declaracion-InfIterna', [IncorporacionesController::class,'generarDeclaracionInfInterna'])->name('eval.gen-declaracion-InfIterna');
Route::post('/incorporaciones/{incorporacionId}/gen-form-cambio-item', [IncorporacionesController::class,'generarFormularioCambioItem'])->name('eval.gen-form-cambio-item');
Route::post('/incorporaciones/{incorporacionId}/gen-form-documentos-cambio-item', [IncorporacionesController::class,'generarFormularioDocumentosCambioItem'])->name('eval.gen-form-documentos-cambio-item');
//durante la incorporacion
Route::post('/incorporaciones/{incorporacionId}/gen-form-multiple', [IncorporacionesController::class,'genFormMultiple'])->name('inc.genFormMultiple');
Route::post('/incorporaciones/{incorporacionId}/gen-form-informe-con-nota', [IncorporacionesController::class,'genFormInformeNota'])->name('inc.genFormInformeNota');
Route::post('/incorporaciones/{incorporacionId}/gen-form-informe-con-minuta', [IncorporacionesController::class,'genFormInformeMinuta'])->name('inc.genFormInformeMinuta');
Route::post('/incorporaciones/{incorporacionId}/gen-form-RAP', [IncorporacionesController::class,'genFormRAP'])->name('inc.genFormRAP');
Route::post('/incorporaciones/{incorporacionId}/gen-form-memo', [IncorporacionesController::class,'genFormMemo'])->name('inc.genFormMemo');
Route::post('/incorporaciones/{incorporacionId}/gen-form-acta-de-posesion', [IncorporacionesController::class,'genFormActaDePosesion'])->name('inc.genFormActaDePosesion');
Route::post('/incorporaciones/{incorporacionId}/gen-form-acta-de-entrega', [IncorporacionesController::class,'genFormActaDeEntrega'])->name('inc.genFormActaDeEntrega');
Route::post('/incorporaciones/{incorporacionId}/gen-form-RemisionDeDocumentos', [IncorporacionesController::class,'genFormRemisionDeDocumentos'])->name('inc.genFormRemisionDeDocumentos');
Route::post('/incorporaciones/{incorporacionId}/gen-form-declaracion-incompatibilidad', [IncorporacionesController::class,'genFormDeclaracionIncompatibilidad'])->name('inc.genFormDeclaracionIncompatibilidad');
Route::post('/incorporaciones/{incorporacionId}/gen-form-acta-compromiso-confidencialidad', [IncorporacionesController::class,'genFormActaCompromisoConfidencialidad'])->name('inc.genFormActaCompromisoConfidencialidad');
Route::post('/incorporaciones/{incorporacionId}/gen-form-idioma', [IncorporacionesController::class,'genFormActaIdioma'])->name('inc.genFormActaIdioma');

