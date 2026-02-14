<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCredential extends Model
{
    protected $fillable = [
        'payment_method_id',
        'nombre',
        'clave',
        'secreto',
        'datos_adicionales',
        'banco',
        'cuenta',
        'cedula',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
