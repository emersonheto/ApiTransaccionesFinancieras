<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $fillable = [
        'cuenta_id',
        'tipo',
        'monto',
        'comision',
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class);
    }

    // public function cuentaDestino()
    // {
    //     return $this->belongsTo(Cuenta::class, 'cuenta_destino_id');
    // }
}
