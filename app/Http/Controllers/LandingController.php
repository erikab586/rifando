<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Boleto;
use App\Models\Compra;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Filtrar rifas activas con fechas válidas
        $rifas = Rifa::where('status', 1)
            ->where('start', '<=', now())
            ->where('end', '>=', now())
            ->orderBy('end', 'asc')
            ->paginate(12);

        return view('landing.index', compact('rifas'));
    }

    public function detalle(Rifa $rifa)
    {
        $boletos = $rifa->boletos()->disponible()->paginate(100);
        $total = $rifa->boletos()->count();
        $vendidos = $rifa->boletos()->vendido()->count();
        $disponibles = $rifa->boletos()->disponible()->count();
        $porcentaje_vendido = $total > 0 ? round(($vendidos / $total) * 100, 2) : 0;
        $estadisticas = [
            'total' => $total,
            'disponibles' => $disponibles,
            'vendidos' => $vendidos,
            'porcentaje_vendido' => $porcentaje_vendido,
        ];

        return view('landing.detalle', compact('rifa', 'boletos', 'estadisticas'));
    }

    public function comprar(Request $request, Rifa $rifa)
    {
        $boletosIds = $request->input('boletos_ids');
        $boletos = $boletosIds ? Boleto::whereIn('id', explode(',', $boletosIds))->get() : collect();
        $paymentMethods = PaymentMethod::where('activo', true)->with('paymentCredentials')->get();
        
        return view('landing.comprar', compact('rifa', 'boletos', 'paymentMethods'));
    }

    public function verificarBoleto(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:1',
            'rifa_id' => 'required|exists:rifas,id',
        ]);

        $boleto = Boleto::where('numero', $request->numero)
            ->where('rifa_id', $request->rifa_id)
            ->first();

        if (!$boleto) {
            return response()->json(['disponible' => false, 'mensaje' => 'Boleto no existe']);
        }

        return response()->json([
            'disponible' => $boleto->status === '0',
            'estado' => $this->obtenerEstadoBoleto($boleto->status),
        ]);
    }

    public function exitosa(Compra $compra)
    {
        $compra->load('boletos', 'paymentMethod.paymentCredentials');
        return view('landing.compra-exitosa', compact('compra'));
    }

    private function obtenerEstadoBoleto($status)
    {
        return match ($status) {
            '0' => 'Disponible',
            '1' => 'Reservado',
            '2' => 'Comprado',
            '3' => 'Cancelado',
            default => 'Desconocido',
        };
    }
}
