<?php

namespace App\Services;

use App\Models\Compra;
use App\Models\Boleto;
use App\Models\Rifa;
use App\Models\Cupon;
use Illuminate\Support\Collection;

class CompraService
{
    public function crearCompra(array $datos): Compra
    {
        $rifa = Rifa::findOrFail($datos['rifa_id']);
        $boletoIds = $datos['boletos_ids'];
        
        // Validar que los boletos existan y estén disponibles
        $boletos = Boleto::whereIn('id', $boletoIds)
            ->where('rifa_id', $rifa->id)
            ->get();

        if ($boletos->count() !== count($boletoIds)) {
            throw new \Exception('Algunos boletos no existen o no pertenecen a esta rifa');
        }

        foreach ($boletos as $boleto) {
            if ($boleto->status !== '0') {
                throw new \Exception("El boleto #{$boleto->numero} no está disponible");
            }
        }

        // Calcular total
        $total = $boletos->count() * $rifa->amount;

        // Aplicar cupón si existe
        if (!empty($datos['cupon'])) {
            $cupon = Cupon::where('codigo', $datos['cupon'])
                ->activo()
                ->first();

            if ($cupon && $total >= $cupon->min_apply) {
                $total -= $cupon->amount;
            }
        }

        // Crear compra
        $compra = Compra::create([
            'rifa_id' => $rifa->id,
            'cliente' => $datos['cliente'],
            'apellido' => $datos['apellido'],
            'cedula' => $datos['cedula'],
            'telefono' => $datos['telefono'],
            'email' => $datos['email'] ?? null,
            'estado' => $datos['estado'] ?? null,
            'status' => '0',
            'total' => $total,
        ]);

        // Asociar boletos y marcar como reservados
        foreach ($boletos as $boleto) {
            $boleto->update(['status' => '1']); // 1 = Reservado
            $compra->boletos()->attach($boleto->id);
        }

        return $compra;
    }

    public function confirmarPago(Compra $compra): void
    {
        $compra->update(['status' => '2']); // 2 = Pagado

        // Marcar boletos como comprados
        foreach ($compra->boletos as $boleto) {
            $boleto->update(['status' => '2']);
        }
    }

    public function cancelarCompra(Compra $compra): void
    {
        // Liberar boletos
        foreach ($compra->boletos as $boleto) {
            $boleto->update(['status' => '0']); // 0 = Disponible
        }

        $compra->update(['status' => '3']); // 3 = Cancelado
    }

    public function obtenerEstadisticas(Rifa $rifa): array
    {
        $boletos = $rifa->boletos();
        $total = $boletos->count();
        $vendidos = $boletos->vendido()->count();
        $disponibles = $total - $vendidos;

        return [
            'total' => $total,
            'disponibles' => $disponibles,
            'vendidos' => $vendidos,
            'porcentaje_vendido' => $total > 0 ? round(($vendidos / $total) * 100, 2) : 0,
            'ingresos' => $rifa->compras()->pagado()->sum('total'),
        ];
    }
}
