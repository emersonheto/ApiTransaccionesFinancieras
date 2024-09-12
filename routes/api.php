<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuentaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('cuentas', [CuentaController::class, 'listarCuentas']);
Route::get('cuentas/{id}', [CuentaController::class, 'verCuenta']);
Route::post('cuentas/{id}/depositar', [CuentaController::class, 'procesarDeposito']);
Route::post('cuentas/{id}/retirar', [CuentaController::class, 'procesarRetiro']);
Route::post('cuentas/{id}/transferir', [CuentaController::class, 'procesarTransferencia']);



