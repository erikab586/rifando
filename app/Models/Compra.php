<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'cliente',
        'apellido',
        'cedula',
        'telefono',
        'estado',
        'email',
        'referencia',
        'ref_link',
        'mercadopago_id',
        'rifa_id',
        'status',
        'total',
        'fecha_recordatorio',
        'contador_recien_reservado',
        'notificado_pago_mercadopago',
        'payment_method_id',
        'pago_estado',
        'fecha_pago',
        'cupon',
        'comprobante_pago',
    ];

    protected $casts = [
        'fecha_recordatorio' => 'datetime',
        'total' => 'integer',
        'fecha_pago' => 'datetime',
    ];

    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

    public function boletos()
    {
        return $this->belongsToMany(Boleto::class, 'boleto_compra');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getPaymentCredentialAttribute()
    {
        if (!$this->paymentMethod) return null;
        return PaymentCredential::where('payment_method_id', $this->payment_method_id)
            ->where('activo', true)
            ->first();
    }

    public function scopePendiente($query)
    {
        return $query->where('status', '0');
    }

    public function scopeReservado($query)
    {
        return $query->where('status', '1');
    }

    public function scopePagado($query)
    {
        return $query->where('status', '2');
    }

    public function scopeCancelado($query)
    {
        return $query->where('status', '3');
    }
}
