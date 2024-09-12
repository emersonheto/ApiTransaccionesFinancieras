<?php

namespace Database\Factories;

use App\Models\Cuenta;
use App\Models\Transaccion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CuentaFactory extends Factory
{
    protected $model = Cuenta::class;

    public function definition()
    {
        return [
            'saldo' => $this->faker->randomFloat(2, 100, 10000),
            'tipo_cuenta' => $this->faker->randomElement(['CuentaEstandar', 'CuentaPremium']),
            'titular_nombre'=> $this->faker->name,
            'titular_direccion'=> $this->faker->address,
        ];
    }

    // public function withHistorial($cantidad = 5)
    // {
    //     return $this->has(
    //         Transaccion::factory()->count($cantidad),'transacciones'
    //     );
    // }
}