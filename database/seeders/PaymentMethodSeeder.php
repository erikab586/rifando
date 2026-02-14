<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::firstOrCreate(
            ['slug' => 'paypal'],
            [
                'nombre' => 'PayPal',
                'descripcion' => 'Paga de manera segura a través de PayPal',
                'requiere_pago_inmediato' => true,
                'activo' => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['slug' => 'binance_pay'],
            [
                'nombre' => 'Binance Pay',
                'descripcion' => 'Paga con criptomonedas usando Binance Pay',
                'requiere_pago_inmediato' => true,
                'activo' => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['slug' => 'mercadopago'],
            [
                'nombre' => 'MercadoPago',
                'descripcion' => 'Paga de manera segura a través de MercadoPago',
                'requiere_pago_inmediato' => true,
                'activo' => true,
            ]
        );

        PaymentMethod::firstOrCreate(
            ['slug' => 'transferencia_bancaria'],
            [
                'nombre' => 'Transferencia Bancaria',
                'descripcion' => 'Sube el comprobante de tu transferencia bancaria',
                'requiere_pago_inmediato' => false,
                'activo' => true,
            ]
        );
    }
}

