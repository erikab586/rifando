<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Boleto;
use App\Models\Rifa;
use App\Models\Cupon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with('rifa')->paginate(20);
        return view('admin.compras.index', compact('compras'));
    }

    public function show(Compra $compra)
    {
        $compra->load('rifa', 'boletos');
        return view('admin.compras.show', compact('compra'));
    }

    public function update(Request $request, Compra $compra)
    {
        $validated = $request->validate([
            'status' => 'required|in:0,1,2,3',
        ]);

        $compra->update($validated);

        return back()->with('success', 'Compra actualizada');
    }

    public function crearCompra(Request $request)
    {
        // Convertir string de IDs a array
        $boletosIdsString = $request->input('boletos_ids');
        $boletosIds = !empty($boletosIdsString) ? explode(',', $boletosIdsString) : [];

        $validated = $request->validate([
            'rifa_id' => 'required|exists:rifas,id',
            'cliente' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:11',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|max:100',
            'estado' => 'nullable|string|max:100',
            'cupon' => 'nullable|string|max:50',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'comprobante_pago' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Validar que exista al menos un boleto
        if (empty($boletosIds)) {
            return back()->withErrors(['boletos_ids' => 'Debes seleccionar al menos un boleto.']);
        }

        // Validar que todos los IDs de boletos existan
        $boletos = Boleto::whereIn('id', $boletosIds)->get();
        if ($boletos->count() !== count($boletosIds)) {
            return back()->withErrors(['boletos_ids' => 'Algunos boletos no existen.']);
        }

        // Validar que TODOS los boletos estén disponibles (status = 0)
        $boletosNoDisponibles = $boletos->filter(fn($b) => $b->status !== '0')->map(fn($b) => $b->numero);
        if ($boletosNoDisponibles->count() > 0) {
            $numerosStr = $boletosNoDisponibles->implode(', ');
            return back()->withErrors([
                'boletos_ids' => "Los boletos #$numerosStr no están disponibles. Ya fueron comprados o están apartados."
            ]);
        }

        $rifa = Rifa::findOrFail($validated['rifa_id']);
        $total = count($boletosIds) * $rifa->amount;

        // Aplicar cupón si existe
        if ($validated['cupon']) {
            $cupon = Cupon::where('codigo', $validated['cupon'])
                ->activo()
                ->first();

            if ($cupon && $total >= $cupon->min_apply) {
                $total -= $cupon->amount;
            }
        }

        // Guardar comprobante si existe
        $comprobantePath = null;
        if ($request->hasFile('comprobante_pago')) {
            $comprobantePath = $request->file('comprobante_pago')->store('comprobantes', 'public');
        }

        $compra = Compra::create([
            'rifa_id' => $validated['rifa_id'],
            'cliente' => $validated['cliente'],
            'apellido' => $validated['apellido'],
            'cedula' => $validated['cedula'],
            'telefono' => $validated['telefono'],
            'email' => $validated['email'] ?? null,
            'estado' => $validated['estado'] ?? null,
            'status' => '0',
            'total' => $total,
            'payment_method_id' => $validated['payment_method_id'],
            'pago_estado' => 'pendiente',
            'comprobante_pago' => $comprobantePath,
        ]);

        // Asociar boletos a la compra y cambiar status a apartado (1)
        foreach ($boletosIds as $boleto_id) {
            $boleto = Boleto::findOrFail($boleto_id);
            $boleto->update(['status' => '1']); // Marcar como apartado
            $compra->boletos()->attach($boleto_id);
        }

        return redirect()->route('landing.compra.exitosa', $compra)
            ->with('success', 'Compra registrada exitosamente');
    }

    public function confirmarPago(Compra $compra)
    {
        $compra->update(['status' => '2']);

        // Marcar boletos como pagados
        foreach ($compra->boletos as $boleto) {
            $boleto->update(['status' => '2']);
        }

        return back()->with('success', 'Pago confirmado');
    }

    public function subirComprobante(Request $request, Compra $compra)
    {
        try {
            // Validar que la compra exista
            if (!$compra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Compra no encontrada'
                ], 404);
            }

            $validated = $request->validate([
                'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ]);

            // Guardar comprobante
            if ($request->hasFile('comprobante')) {
                $comprobantePath = $request->file('comprobante')->store('comprobantes', 'public');
                
                $compra->update([
                    'comprobante_pago' => $comprobantePath,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Comprobante guardado correctamente'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo guardar el archivo'
            ], 400);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validación fallida: ' . implode(', ', $e->errors()['comprobante'] ?? ['Error desconocido'])
            ], 422);
        } catch (\Throwable $e) {
            // Captura CUALQUIER error y devuelve JSON
            \Log::error('Error en subirComprobante: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function descargarComprobante(Compra $compra)
    {
        // Cargar relaciones necesarias
        $compra->load('rifa', 'boletos', 'paymentMethod');

        // Generar PDF
        $pdf = Pdf::loadView('landing.comprobantes.ticket', compact('compra'));

        // Configurar el tamaño del papel para media carta (8.5" x 5.5" = 215.9mm x 139.7mm)
        // En puntos: 8.5" = 612 pt, 5.5" = 396 pt
        $pdf->setPaper([0, 0, 612, 396], 'landscape'); // Media carta horizontal

        // Descargar el PDF
        return $pdf->download('comprobante_' . str_pad($compra->id, 8, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function descargarTicket(Compra $compra)
    {
        // Cargar relaciones necesarias
        $compra->load('rifa', 'boletos', 'paymentMethod');

        // Generar PDF
        $pdf = Pdf::loadView('landing.comprobantes.ticket-simple', compact('compra'));

        // Configurar el tamaño del papel para ticket de 80mm de ancho
        // 80mm = 226.77 pt, altura variable según contenido
        $pdf->setPaper([0, 0, 226.77, 566.93], 'portrait'); // Ticket 80mm

        // Descargar el PDF
        return $pdf->download('ticket_' . str_pad($compra->id, 8, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function verTicket(Compra $compra)
    {
        // Cargar relaciones necesarias
        $compra->load('rifa', 'boletos', 'paymentMethod');

        return view('landing.comprobantes.ticket-simple', compact('compra'));
    }

    public function destroy(Compra $compra)
    {
        // Liberar boletos (volver a status 0 = disponible)
        foreach ($compra->boletos as $boleto) {
            $boleto->update(['status' => '0']);
        }

        // Desasociar boletos
        $compra->boletos()->detach();

        $compra->delete();
        return redirect()->route('admin.compras.index')
            ->with('success', 'Compra eliminada y boletos liberados');
    }

    public function pagarMercadoPago(Compra $compra)
    {
        // Verificar que el método de pago sea MercadoPago
        if (!$compra->paymentMethod || !str_contains($compra->paymentMethod->nombre, 'MercadoPago')) {
            return redirect()->route('pago.detalle', $compra)
                ->with('error', 'Método de pago inválido');
        }

        // Cargar credenciales
        $credential = $compra->payment_credential;

        if (!$credential) {
            return view('landing.pagos.mercadopago', compact('compra'))
                ->with('error', 'Credenciales de MercadoPago no configuradas');
        }

        try {
            // Configurar SDK de MercadoPago
            \MercadoPago\SDK::setAccessToken($credential->clave);

            // Crear preferencia de pago
            $preference = new \MercadoPago\Preference();

            // Crear item
            $item = new \MercadoPago\Item();
            $item->title = "Compra Rifa: " . $compra->rifa->name;
            $item->quantity = $compra->boletos->count();
            $item->unit_price = round($compra->total / $compra->boletos->count(), 2);
            
            $preference->items = array($item);

            // Datos del comprador
            $payer = new \MercadoPago\Payer();
            $payer->name = $compra->cliente;
            $payer->surname = $compra->apellido;
            $payer->email = $compra->email;
            $payer->phone = array(
                "area_code" => "",
                "number" => $compra->telefono
            );
            $payer->address = array(
                "street_name" => $compra->estado ?? "No especificado",
                "street_number" => null,
                "zip_code" => null
            );

            $preference->payer = $payer;

            // URLs de retorno
            $preference->back_urls = array(
                "success" => route('compra.mercadopago-success', $compra),
                "failure" => route('compra.mercadopago-failure', $compra),
                "pending" => route('compra.mercadopago-pending', $compra)
            );

            $preference->auto_return = "approved";
            
            // Reference ID
            $preference->external_reference = (string)$compra->id;
            
            // Metadata
            $preference->metadata = array(
                "compra_id" => $compra->id,
                "cliente" => $compra->cliente,
                "cedula" => $compra->cedula
            );

            // Guardar preferencia
            $preference->save();

            // Redirigir a MercadoPago
            return redirect($preference->init_point);

        } catch (\Exception $e) {
            \Log::error('Error MercadoPago: ' . $e->getMessage());
            return view('landing.pagos.mercadopago', compact('compra'))
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function mercadopagoSuccess(Compra $compra)
    {
        // Actualizar estado de la compra
        $compra->update([
            'status' => '2',  // Pagado
            'pago_estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        return redirect()->route('pago.detalle', $compra)
            ->with('success', '✅ ¡Pago completado exitosamente!');
    }

    public function mercadopagoFailure(Compra $compra)
    {
        return redirect()->route('pago.detalle', $compra)
            ->with('error', '❌ El pago fue rechazado. Por favor, intenta nuevamente.');
    }

    public function mercadopagoPending(Compra $compra)
    {
        // Actualizar a pendiente de confirmación
        $compra->update([
            'pago_estado' => 'pendiente',
        ]);

        return redirect()->route('pago.detalle', $compra)
            ->with('warning', '⏳ Tu pago está en proceso de verificación. Te notificaremos cuando se confirme.');
    }
}
