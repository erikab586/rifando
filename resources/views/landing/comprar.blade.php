@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Formulario de Compra</h1>

            @if($boletos->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                    <p class="text-yellow-800 font-semibold">⚠️ No seleccionaste ningún boleto</p>
                    <p class="text-yellow-700 text-sm mt-1">
                        <a href="{{ route('landing.detalle', $rifa) }}" class="underline hover:no-underline">Vuelve atrás y selecciona los boletos que deseas comprar</a>
                    </p>
                </div>
            @endif

            <form action="{{ route('compra.crear') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="rifa_id" value="{{ $rifa->id }}">
                <input type="hidden" name="boletos_ids" value="{{ $boletos->pluck('id')->join(',') }}">

                <!-- Boletos Seleccionados -->
                @if($boletos->isNotEmpty())
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-3">Boletos Seleccionados</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($boletos as $boleto)
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    #{{ $boleto->numero }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Información Personal -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Personal</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                            <input type="text" name="cliente" required value="{{ old('cliente') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Juan">
                            @error('cliente') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                            <input type="text" name="apellido" required value="{{ old('apellido') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Pérez">
                            @error('apellido') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cédula *</label>
                            <input type="text" name="cedula" required value="{{ old('cedula') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="12345678">
                            @error('cedula') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                            <input type="tel" name="telefono" required value="{{ old('telefono') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="+58 412 1234567">
                            @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="juan@example.com">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <input type="text" name="estado" value="{{ old('estado') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Miranda">
                            @error('estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Cupón -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Código de Descuento (Opcional)</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cupón</label>
                        <input type="text" name="cupon" value="{{ old('cupon') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ingresa tu código de descuento">
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Método de Pago *</h2>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($paymentMethods as $method)
                            {{-- Ocultar MercadoPago (id=7) y Binance Pay (id=6) --}}
                            @if(!in_array($method->id, [6, 7]))
                            <label data-payment-method class="relative flex items-start p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition @if($loop->first && !in_array($method->id, [6, 7])) border-blue-500 bg-blue-50 @endif">
                                <div class="flex items-center h-6">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" 
                                           @if($loop->first && !in_array($method->id, [6, 7])) checked @endif
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                           onchange="updatePaymentMethod(this)">
                                </div>
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900">{{ $method->nombre }}</p>
                                    <p class="text-sm text-gray-600">{{ $method->descripcion }}</p>
                                    @if($method->requiere_pago_inmediato)
                                        <span class="inline-block mt-1 px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded">Pago Inmediato Requerido</span>
                                    @else
                                        <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Pago Opcional</span>
                                    @endif
                                </div>
                            </label>
                            @endif
                        @endforeach
                    </div>
                    @error('payment_method_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Resumen -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumen de Compra</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Rifa:</span>
                            <span class="font-semibold">{{ $rifa->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Cantidad de Boletos:</span>
                            <span class="font-semibold">{{ $boletos->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Precio por Boleto:</span>
                            <span>${{ number_format($rifa->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                            <span>Total:</span>
                            <span id="total-resumen">${{ number_format($boletos->count() * $rifa->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <a href="{{ route('landing.detalle', $rifa) }}" 
                       class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition text-center">
                        Volver
                    </a>
                    @if($boletos->isNotEmpty())
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Confirmar Compra
                        </button>
                    @else
                        <button type="button" disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg font-semibold cursor-not-allowed">
                            Confirmar Compra
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Datos de métodos de pago con información bancaria desde payment_credentials
    const metodosInfo = {
        @foreach($paymentMethods as $method)
            {{ $method->id }}: {
                nombre: '{{ $method->nombre }}',
                esTransferencia: {{ $method->slug == 'transferencia_bancaria' ? 'true' : 'false' }},
                datos: @if($method->paymentCredentials && $method->paymentCredentials->count() > 0)
                    @json($method->paymentCredentials->first()->datos_adicionales ?? null)
                @else
                    null
                @endif
            },
        @endforeach
    };

    console.log('Métodos Info:', metodosInfo);

    function updatePaymentMethod(radio) {
        // Remove styling from all payment method labels
        const labels = document.querySelectorAll('label[data-payment-method]');
        labels.forEach(label => {
            label.classList.remove('border-blue-500', 'bg-blue-50');
            label.classList.add('border-gray-300');
        });
        
        // Add styling to selected payment method
        const selectedLabel = radio.closest('label');
        selectedLabel.classList.remove('border-gray-300');
        selectedLabel.classList.add('border-blue-500', 'bg-blue-50');

        // Mostrar/ocultar sección de datos bancarios
        const paymentMethodId = parseInt(radio.value);
        const comprobanteSection = document.getElementById('comprobante-section');
        const metodoInfo = metodosInfo[paymentMethodId];
        
        console.log('Selected method ID:', paymentMethodId);
        console.log('Method info:', metodoInfo);
        
        if (metodoInfo && metodoInfo.esTransferencia && metodoInfo.datos) {
            // Solo mostrar si es transferencia Y hay datos
            comprobanteSection.style.display = 'block';
            mostrarDatosBancarios(metodoInfo.datos);
        } else {
            comprobanteSection.style.display = 'none';
        }
    }

    function mostrarDatosBancarios(datos) {
        const datosContent = document.getElementById('datos-content');
        
        console.log('Datos bancarios:', datos);

        let html = '<div class="bg-white p-3 rounded border border-purple-200 space-y-2">';
        
        if (datos.banco) {
            html += `<div class="flex justify-between"><span class="font-semibold text-gray-700">Banco:</span><span class="text-gray-900">${datos.banco}</span></div>`;
        }
        if (datos.cuenta) {
            html += `<div class="flex justify-between border-t pt-2"><span class="font-semibold text-gray-700">Cuenta:</span><span class="text-gray-900 font-mono">${datos.cuenta}</span></div>`;
        }
        if (datos.cedula) {
            html += `<div class="flex justify-between border-t pt-2"><span class="font-semibold text-gray-700">Cédula Titular:</span><span class="text-gray-900">${datos.cedula}</span></div>`;
        }
        if (datos.telefono) {
            html += `<div class="flex justify-between border-t pt-2"><span class="font-semibold text-gray-700">Teléfono:</span><span class="text-gray-900">${datos.telefono}</span></div>`;
        }
        
        html += '</div>';
        datosContent.innerHTML = html;
    }

    // Initialize styling on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checkedRadio = document.querySelector('input[name="payment_method_id"]:checked');
        if (checkedRadio) {
            updatePaymentMethod(checkedRadio);
        }
    });
</script>
@endsection
