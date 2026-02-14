<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class PagoPublicController extends Controller
{
    /**
     * Mostrar página de seguimiento de pago con cédula y compra
     */
    public function verificar()
    {
        return view('landing.pagos.verificar');
    }

    /**
     * Buscar pagos por cédula
     */
    public function buscar(Request $request)
    {
        $validated = $request->validate([
            'cedula' => 'required|string|max:11',
        ]);

        $compras = Compra::where('cedula', $validated['cedula'])
            ->with('rifa', 'paymentMethod')
            ->latest()
            ->get();

        if ($compras->isEmpty()) {
            return back()->withErrors(['cedula' => 'No se encontraron compras con esa cédula.']);
        }

        return view('landing.pagos.listado', compact('compras'));
    }

    /**
     * Ver detalles de un pago específico
     */
    public function detalle(Compra $compra)
    {
        $compra->load('rifa', 'paymentMethod', 'boletos');
        return view('landing.pagos.detalle', compact('compra'));
    }

    /**
     * Cambiar método de pago en vista pública
     */
    public function cambiarMetodo(Compra $compra)
    {
        $validated = request()->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $compra->update([
            'payment_method_id' => $validated['payment_method_id'],
        ]);

        return back()->with('success', 'Método de pago actualizado correctamente. Puedes proceder con el nuevo método.');
    }
}
