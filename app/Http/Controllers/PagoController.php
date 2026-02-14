<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['rifa', 'paymentMethod' => function($q) {
            $q->where('activo', 1);
        }])
            ->whereHas('paymentMethod', fn($q) => $q->where('activo', 1))
            ->when(request('rifa_id'), fn($q) => $q->where('rifa_id', request('rifa_id')))
            ->when(request('pago_estado'), fn($q) => $q->where('pago_estado', request('pago_estado')))
            ->when(request('payment_method_id'), fn($q) => $q->where('payment_method_id', request('payment_method_id')))
            ->paginate(15);

        return view('admin.pagos.index', compact('compras'));
    }

    public function marcarPagado(Compra $compra)
    {
        $compra->update([
            'status' => '2',  // 2 = Pagado
            'pago_estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        return back()->with('success', 'Pago marcado como pagado');
    }

    public function marcarCancelado(Compra $compra)
    {
        $compra->update([
            'pago_estado' => 'cancelado',
        ]);

        // Liberar boletos (volver a status 0 = disponible)
        foreach ($compra->boletos as $boleto) {
            $boleto->update(['status' => '0']);
        }

        return back()->with('success', 'Pago marcado como cancelado y boletos liberados');
    }

    public function cambiarMetodo(Compra $compra)
    {
        $validated = request()->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $compra->update([
            'payment_method_id' => $validated['payment_method_id'],
        ]);

        return back()->with('success', 'Método de pago actualizado correctamente');
    }

    public function subirComprobante(Request $request, Compra $compra)
    {
        $validated = $request->validate([
            'comprobante_pago' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Eliminar comprobante anterior si existe
        if ($compra->comprobante_pago) {
            \Storage::delete($compra->comprobante_pago);
        }

        // Guardar nuevo comprobante
        $path = $request->file('comprobante_pago')->store('comprobantes', 'public');

        $compra->update([
            'comprobante_pago' => $path,
        ]);

        return back()->with('success', 'Comprobante de pago subido correctamente');
    }
}
