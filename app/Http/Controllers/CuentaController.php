<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\HistorialTransaccion;

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
        // dd("hika");
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
    



    // public function retirar(Request $request, $id)
    // {
    //     $cuenta = Cuenta::findOrFail($id);
    //     $monto = $request->input('monto');
    //     $comision = 0.02 * $monto;
    
    //     if ($cuenta->tipo_cuenta == 'CuentaEstandar' && $cuenta->saldo - ($monto + $comision) < 100) {
    //         return response()->json(['error' => 'Saldo insuficiente'], 400);
    //     }
    
    //     $cuenta->saldo -= ($monto + $comision);
    //     $cuenta->save();
    
    //     Transaccion::create([
    //         'cuenta_id' => $cuenta->id,
    //         'tipo' => 'retiro',
    //         'monto' => $monto,
    //         'comision' => $comision
    //     ]);
    
    //     return response()->json(['message' => 'Retiro realizado con éxito']);
    // }
    
    // public function transferir(Request $request, $id)
    // {
    //     $cuentaOrigen = Cuenta::findOrFail($id);
    //     $cuentaDestino = Cuenta::findOrFail($request->input('cuentaDestinoId'));
    //     $monto = $request->input('monto');
    //     $comision = 0.01 * $monto;
    
    //     if ($cuentaOrigen->tipo_cuenta == 'CuentaEstandar' && $cuentaOrigen->saldo - ($monto + $comision) < 100) {
    //         return response()->json(['error' => 'Saldo insuficiente'], 400);
    //     }
    
    //     $cuentaOrigen->saldo -= ($monto + $comision);
    //     $cuentaDestino->saldo += $monto;
    //     $cuentaOrigen->save();
    //     $cuentaDestino->save();
    
    //     Transaccion::create([
    //         'cuenta_id' => $cuentaOrigen->id,
    //         'tipo' => 'transferencia',
    //         'monto' => $monto,
    //         'comision' => $comision
    //     ]);
    
    //     return response()->json(['message' => 'Transferencia realizada con éxito']);
    // }
    



}
