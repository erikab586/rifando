@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-4">¡Compra Realizada Exitosamente!</h1>
            
            <p class="text-gray-600 text-lg mb-8">
                Gracias por tu compra. Hemos registrado tu información y tus boletos.
            </p>

            <!-- Detalles de la Compra -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles de tu Compra</h2>
                
                <!-- Botón Descargar Comprobante -->
                <div class="mb-6 flex gap-3 flex-wrap">
                    <a href="{{ route('compra.descargar-comprobante', $compra) }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                        📄 Descargar Comprobante PDF
                    </a>
                   <a href="{{ route('compra.descargar-comprobante', $compra) }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                        🖨️ Imprimir
                    </a>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">ID de Compra</p>
                        <p class="font-bold text-lg">#{{ $compra->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Rifa</p>
                        <p class="font-bold text-lg">{{ $compra->rifa->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Nombre</p>
                        <p class="font-bold">{{ $compra->cliente }} {{ $compra->apellido }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Teléfono</p>
                        <p class="font-bold">{{ $compra->telefono }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Cantidad de Boletos</p>
                        <p class="font-bold text-lg">{{ $compra->boletos()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Monto Total</p>
                        <p class="font-bold text-lg text-green-600">${{ number_format($compra->total, 2) }}</p>
                    </div>
                    @if($compra->paymentMethod)
                    <div>
                        <p class="text-gray-600 text-sm">Método de Pago</p>
                        <p class="font-bold text-lg text-purple-600">{{ $compra->paymentMethod->nombre }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t">
                    <h3 class="font-semibold text-gray-900 mb-3">Boletos Asignados</h3>
                    <div class="grid grid-cols-5 md:grid-cols-8 gap-2">
                        @foreach($compra->boletos as $boleto)
                            <div class="aspect-square bg-blue-500 text-white rounded-lg flex items-center justify-center font-bold">
                                {{ $boleto->numero }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Próximos Pasos -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8 text-left">
                <h2 class="text-lg font-semibold text-blue-900 mb-3">Próximos Pasos</h2>
                <ol class="list-decimal list-inside space-y-2 text-blue-800">
                    <li>Completa el pago a través del método que seleccionaste</li>
                    <li>Recibirás una confirmación por email</li>
                    <li>Tus boletos serán finalizados automáticamente al confirmar el pago</li>
                    <li>Participarás automáticamente en el sorteo</li>
                </ol>
            </div>

            <!-- Sección de Pago según Método -->
            @if($compra->paymentMethod)
                @php
                    $credential = $compra->payment_credential;
                @endphp
                {{-- Ocultar MercadoPago (id=7) y Binance Pay (id=6) --}}
                @if(!in_array($compra->paymentMethod->id, [6, 7]))
                @if($compra->paymentMethod->slug == 'paypal')
                    <!-- Métodos de Pago en Línea -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-green-900 mb-4">Método de Pago: {{ $compra->paymentMethod->nombre }}</h3>
                        <p class="text-green-800 mb-4">Completa tu pago de forma segura usando el siguiente botón:</p>
                        <button onclick="irAPagar()" class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                            💳 Pagar Ahora - ${{ number_format($compra->total, 2) }}
                        </button>
                        <p class="text-xs text-green-700 mt-2">Serás redirigido al portal seguro de {{ $compra->paymentMethod->nombre }}</p>
                    </div>
                @elseif($compra->paymentMethod->slug == 'transferencia_bancaria')
                    <!-- Método de Transferencia Bancaria -->
                    @if($credential)
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4">📋 Transferencia Bancaria</h3>
                            <p class="text-purple-800 mb-4">Tienes dos opciones para completar tu pago:</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button onclick="abrirModalDatos()" class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                                    👁️ Ver Datos Bancarios
                                </button>
                                <button onclick="abrirModalSubirComprobante()" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                    📤 Subir Comprobante
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
                @endif
            @endif

            <!-- Modal Datos Bancarios -->
            @if($compra->paymentMethod && $compra->paymentMethod->slug == 'transferencia_bancaria' && $credential)
                <div id="modalDatos" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-40 p-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                        <div class="bg-purple-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                            <h3 class="text-lg font-bold">Datos Bancarios</h3>
                            <button onclick="cerrarModalDatos()" class="text-white hover:bg-purple-700 p-1 rounded">✕</button>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($credential->banco)
                                <div class="border-b pb-3">
                                    <p class="text-sm text-gray-600 font-semibold">Banco</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $credential->banco }}</p>
                                </div>
                            @endif
                            @if($credential->cuenta)
                                <div class="border-b pb-3">
                                    <p class="text-sm text-gray-600 font-semibold">Número de Cuenta</p>
                                    <p class="text-lg font-mono font-bold text-gray-900 break-all">{{ $credential->cuenta }}</p>
                                </div>
                            @endif
                            @if($credential->cedula)
                                <div class="border-b pb-3">
                                    <p class="text-sm text-gray-600 font-semibold">Cédula Titular</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $credential->cedula }}</p>
                                </div>
                            @endif
                            @if($credential->telefono)
                                <div class="border-b pb-3">
                                    <p class="text-sm text-gray-600 font-semibold">Teléfono de Contacto</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $credential->telefono }}</p>
                                </div>
                            @endif
                            <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                                <p class="text-sm text-gray-600 font-semibold">Monto a Transferir</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($compra->total, 2) }}</p>
                            </div>
                            <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded">
                                <strong>💡 Tip:</strong> Después de transferir, sube el comprobante usando el botón "Subir Comprobante" para que verifiquemos tu pago más rápido.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Subir Comprobante -->
            @if($compra->paymentMethod && $compra->paymentMethod->slug == 'transferencia_bancaria')
                <div id="modalComprobante" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-40 p-4">
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                        <div class="bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                            <h3 class="text-lg font-bold">Subir Comprobante de Pago</h3>
                            <button onclick="cerrarModalComprobante()" class="text-white hover:bg-blue-700 p-1 rounded">✕</button>
                        </div>
                        <div class="p-6">
                            <form id="formComprobante" action="{{ route('compra.subir-comprobante', $compra) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="comprobante" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Selecciona tu comprobante
                                    </label>
                                    <input type="file" name="comprobante" id="comprobante" accept=".jpg,.jpeg,.png,.pdf" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-2">Formatos: JPG, PNG, PDF (máximo 5MB)</p>
                                </div>
                                <div class="bg-blue-50 p-3 rounded border border-blue-200">
                                    <p class="text-sm text-blue-800">
                                        <strong>📝 Nota:</strong> Adjunta capturas del comprobante de tu transferencia o pago móvil.
                                    </p>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" onclick="cerrarModalComprobante()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                        Subir
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex gap-3 mt-8">
                <a href="{{ route('landing.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition text-center">
                    Volver al Inicio
                </a>
                <button onclick="window.print()" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Imprimir Comprobante
                </button>
            </div>

            <!-- Mensaje de Contacto -->
            <div class="mt-8 text-gray-600 text-sm">
                <p>¿Tienes dudas? Contáctanos por WhatsApp</p>
                <a href="https://wa.me/5551981296129" class="text-green-600 font-semibold hover:underline">
                    +55 51 98129-6129
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Ocultar elementos que no se deben imprimir */
        body > * > .container > .max-w-2xl > .bg-white > .flex:last-child,
        .mt-8,
        button,
        a {
            display: none !important;
        }

        /* Ajustar el contenedor para impresión */
        body {
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 100%;
            padding: 0 !important;
        }

        .max-w-2xl {
            max-width: 100% !important;
            margin: 0 !important;
        }

        .bg-white {
            padding: 20mm !important;
            box-shadow: none !important;
            background: white !important;
        }

        /* Reducir espacios */
        .mb-4, .mb-6, .mb-8 {
            margin-bottom: 0.5rem !important;
        }

        .mt-6, .mt-8, .mt-2, .mt-1 {
            margin-top: 0.25rem !important;
        }

        .pt-6 {
            padding-top: 0.5rem !important;
        }

        /* Ajustar tamaños de fuente */
        h1 {
            font-size: 1.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        h2, h3 {
            font-size: 1rem !important;
            margin-bottom: 0.25rem !important;
        }

        p {
            margin-bottom: 0.25rem !important;
            font-size: 0.9rem !important;
        }

        /* Ajustar grid */
        .grid {
            gap: 0.5rem !important;
        }

        /* Reducir padding */
        .p-6, .p-8 {
            padding: 0.5rem !important;
        }

        /* Los botones de imprimir deben estar ocultos */
        button[onclick*="window.print"] {
            display: none !important;
        }

        /* Evitar saltos de página no deseados */
        .bg-gray-50, .bg-blue-50, .bg-green-50, .bg-purple-50 {
            page-break-inside: avoid;
        }

        /* Asegurar que todo quepa en una página */
        @page {
            size: A4;
            margin: 10mm;
        }
    }
</style>

<script>
    function irAPagar() {
        const metodo = '{{ $compra->paymentMethod->nombre ?? '' }}';
        const monto = {{ $compra->total }};
        const compraId = {{ $compra->id }};
        const cliente = '{{ $compra->cliente }}';
        const email = '{{ $compra->email }}';

        // URLs de prueba para cada proveedor
        if (metodo.includes('PayPal')) {
            // Redirigir a PayPal en modo sandbox
            window.location.href = `https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sb-test@paypal.com&item_name=Compra%20Rifa%20%23${compraId}&amount=${monto}&currency_code=USD&invoice=${compraId}`;
        } else if (metodo.includes('MercadoPago')) {
            // Redirigir a página de pago MercadoPago
            window.location.href = `/compra/${compraId}/pagar-mercadopago`;
        } else if (metodo.includes('Binance')) {
            // Redirigir a Binance Pay (modo prueba)
            window.location.href = `https://pay.binance.com/web/testpay?orderId=order_${compraId}`;
        }
    }

    function abrirModalDatos() {
        document.getElementById('modalDatos').classList.remove('hidden');
    }

    function cerrarModalDatos() {
        document.getElementById('modalDatos').classList.add('hidden');
    }

    function abrirModalSubirComprobante() {
        document.getElementById('modalComprobante').classList.remove('hidden');
    }

    function cerrarModalComprobante() {
        document.getElementById('modalComprobante').classList.add('hidden');
    }

    // Cerrar modal al hacer click fuera
    document.getElementById('modalDatos')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    document.getElementById('modalComprobante')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Manejar envío del formulario
    document.getElementById('formComprobante')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('comprobante');
        const file = fileInput.files[0];
        
        // Validar ANTES de enviar
        if (!file) {
            alert('⚠️ Selecciona un archivo');
            return;
        }
        
        // Validar tamaño (máx 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('⚠️ El archivo debe ser menor a 5MB');
            return;
        }
        
        // Validar tipo
        const tiposValidos = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!tiposValidos.includes(file.type)) {
            alert('⚠️ Solo se permiten: JPG, PNG, PDF');
            return;
        }
        
        // Si pasó todas las validaciones, enviar
        const formData = new FormData(this);
        
        try {
            const response = await fetch(`{{ route('compra.subir-comprobante', $compra->id) }}`, {
                method: 'POST',
                body: formData
            });

            // Leer el texto primero
            const text = await response.text();
            let data = null;

            // Intentar parsear como JSON
            try {
                data = JSON.parse(text);
            } catch (jsonError) {
                console.error('Error parsing JSON. Response text:', text);
                alert('❌ Error del servidor (respuesta inválida)\n' + text.substring(0, 200));
                return;
            }

            if (response.ok) {
                alert('✅ ' + data.message);
                cerrarModalComprobante();
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('❌ Error (' + response.status + '): ' + data.message);
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('❌ Error de conexión: ' + error.message);
        }
    });

</script>
@endsection
