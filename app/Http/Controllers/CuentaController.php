<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;

class CuentaController extends Controller
{
    public function listarCuentas()
    {
        $cuentas = Cuenta::select('id', 'saldo', 'titular_nombre', 'titular_direccion')->get();
        return response()->json($cuentas);
    }

    public function verCuenta($id)
    {
        $cuenta = Cuenta::with('transacciones')->findOrFail($id);
        return response()->json($cuenta);
    }

    public function procesarDeposito(Request $request, $id)
    {
        $cuenta = Cuenta::findOrFail($id);
        $validated = $request->validate(['monto' => 'required|numeric|min:1']);
        
        $cuenta->saldo += $validated['monto'];
        $cuenta->save();
    
        $cuenta->transacciones()->create([
            'tipo' => 'depósito',
            'monto' => $validated['monto'],
            'comision' => 0
        ]);
    
        return response()->json(['message' => 'Depósito exitoso', 'cuenta' => $cuenta]);
    }

    public function procesarRetiro(Request $request, $id)
    {
        $cuenta = Cuenta::findOrFail($id);
        $validated = $request->validate(['monto' => 'required|numeric|min:1']);
        $comision = 0;

        if ($cuenta->tipo_cuenta == 'CuentaEstandar') {
            $comision = $validated['monto'] * 0.02;
            $total = $validated['monto'] + $comision;
            if ($cuenta->saldo - $total < 100) {
                return response()->json(['error' => 'Saldo insuficiente'], 400);
            }
        } else {
            $total = $validated['monto'];
        }

        $cuenta->saldo -= $total;
        $cuenta->save();

        $cuenta->transacciones()->create([
            'tipo' => 'retiro',
            'monto' => $validated['monto'],
            'comision' => $comision
        ]);

        return response()->json(['message' => 'Retiro exitoso', 'cuenta' => $cuenta]);
    }

    public function procesarTransferencia(Request $request, $id)
    {
        
        
        if ($id == $request->cuentaDestinoId) {
            return response()->json(['error' => 'No puedes transferir dinero a la misma cuenta'], 400);
        }


        $cuentaOrigen = Cuenta::findOrFail($id);
        $cuentaDestino = Cuenta::findOrFail($request->cuentaDestinoId);
        $validated = $request->validate(['monto' => 'required|numeric|min:1']);
        $comision = 0; 
        if ($cuentaOrigen->tipo_cuenta == 'CuentaEstandar') {
            $comision = $validated['monto'] * 0.01;
            $total = $validated['monto'] + $comision;
            if ($cuentaOrigen->saldo - $total < 100) {
                return response()->json(['error' => 'Saldo insuficiente'], 400);
            }
        } else {
            $total = $validated['monto'];
        }
    
        $cuentaOrigen->saldo -= $total;
        $cuentaDestino->saldo += $validated['monto'];
        
        $cuentaOrigen->save();
        $cuentaDestino->save();
    
        $cuentaOrigen->transacciones()->create([
            'tipo' => 'transferencia',
            'monto' => $validated['monto'],
            'comision' => $comision
        ]);
        
        return response()->json(['message' => 'Transferencia exitosa', 'cuenta_origen' => $cuentaOrigen, 'cuenta_destino' => $cuentaDestino]);
    }
    
}
