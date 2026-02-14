<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'requiere_pago_inmediato',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'requiere_pago_inmediato' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function paymentCredentials()
    {
        return $this->hasMany(PaymentCredential::class);
    }
}
