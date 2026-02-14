<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    use HasFactory;

    protected $table = 'cupons';

    protected $fillable = [
        'codigo',
        'descuento',
        'minimo_compra',
        'vigente_desde',
        'vigente_hasta',
        'uso_maximo',
        'usos_actuales',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'vigente_desde' => 'datetime',
            'vigente_hasta' => 'datetime',
            'descuento' => 'decimal:2',
            'minimo_compra' => 'decimal:2',
        ];
    }

    public function esValido()
    {
        $ahora = now();

        // Verificar si está activo
        if ($this->estado !== 'activo') {
            return false;
        }

        // Verificar si está dentro de las fechas válidas
        if ($this->vigente_desde && $ahora < $this->vigente_desde) {
            return false;
        }

        if ($this->vigente_hasta && $ahora > $this->vigente_hasta) {
            return false;
        }

        // Verificar si ha alcanzado el máximo de usos
        if ($this->uso_maximo && $this->usos_actuales >= $this->uso_maximo) {
            return false;
        }

        return true;
    }

    public function aplicar()
    {
        $this->increment('usos_actuales');
    }
}
