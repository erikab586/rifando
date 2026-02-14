<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rifa extends Model
{
    use HasFactory;

    protected $table = 'rifas';

    protected $fillable = [
        'name',
        'slug',
        'img',
        'description',
        'num_boletos',
        'num_adicionales',
        'amount',
        'user_id',
        'mercadopago_id',
        'start',
        'end',
        'descuento',
        'des_max',
        'des_porcentaje',
        'status',
        'vista',
        'bono',
        'zodiaco',
        'etiqueta_saludo_wa',
        'etiqueta_ML_wa',
        'etiqueta_ticket_wa',
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];

    /**
     * Obtener la clave de ruta para el modelo
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function boletos()
    {
        return $this->hasMany(Boleto::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function boletosDisponibles()
    {
        return $this->boletos()->where('status', '0');
    }

    public function boletosVendidos()
    {
        return $this->boletos()->where('status', '!=', '0');
    }
}
