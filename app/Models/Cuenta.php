<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    use HasFactory;
    protected $table = 'cuentas';

    protected $fillable = ['saldo', 'tipo_cuenta', 'titular_nombre', 'titular_direccion'];

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }
}
