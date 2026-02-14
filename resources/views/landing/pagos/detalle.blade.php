@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('pago.verificar') }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Atrás
            </a>
        </div>

        <!-- Estado Pago Principal -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <div class="text-center mb-6">
                @if($compra->pago_estado === 'pagado')
                    <div class="inline-block p-4 bg-green-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-green-900">Pago Confirmado</h1>
                    <p class="text-green-700 mt-2">Tu pago ha sido procesado exitosamente</p>
                @elseif($compra->pago_estado === 'cancelado')
                    <div class="inline-block p-4 bg-red-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-red-900">Pago Cancelado</h1>
                    <p class="text-red-700 mt-2">Este pago ha sido cancelado</p>
                @else
                    <div class="inline-block p-4 bg-yellow-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-yellow-900">Pago Pendiente</h1>
                    <p class="text-yellow-700 mt-2">Tu pago está en espera de confirmación</p>
                @endif
            </div>

            <div class="border-t pt-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Compra #</p>
                        <p class="text-lg font-bold text-gray-900">{{ $compra->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Fecha</p>
                        <p class="text-lg font-bold text-gray-900">{{ $compra->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total</p>
                        <p class="text-lg font-bold text-gray-900">${{ number_format($compra->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Boletos</p>
                        <p class="text-lg font-bold text-gray-900">{{ $compra->boletos->count() }}</p>
                    </div>
                </div>
                
                <!-- Botones de Descarga e Impresión -->
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('compra.descargar-comprobante', $compra) }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                        📄 Descargar Comprobante PDF
                    </a>
                    <button onclick="window.print()" 
                            class="flex items-center gap-2 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg font-semibold hover:bg-gray-400 transition">
                        🖨️ Imprimir
                    </button>
                </div>
            </div>
        </div>

        <!-- Información de la Rifa -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Información de la Rifa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Nombre</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->rifa->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Precio Unitario</p>
                    <p class="text-lg font-bold text-gray-900">${{ number_format($compra->rifa->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Cantidad de Boletos</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->boletos->count() }} boletos</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Método de Pago</p>
                    <p class="text-lg font-bold text-gray-900">
                        @if($compra->paymentMethod)
                            {{ $compra->paymentMethod->nombre }}
                        @else
                            <span class="text-gray-500">No especificado</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Tus Boletos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Tus Boletos</h2>
            @if($compra->boletos->isEmpty())
                <p class="text-gray-600">No hay boletos asociados a esta compra</p>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($compra->boletos as $boleto)
                        <div class="bg-gradient-to-br from-purple-100 to-blue-100 p-4 rounded-lg text-center border-2 border-purple-300">
                            <p class="text-xs text-gray-600 font-semibold">BOLETO #</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $boleto->numero }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Datos del Comprador -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Datos del Comprador</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Nombre</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->cliente }} {{ $compra->apellido }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Cédula</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->cedula }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Teléfono</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->telefono }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Email</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->email ?? 'No proporcionado' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Estado</p>
                    <p class="text-lg font-bold text-gray-900">{{ $compra->estado ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <!-- Comprobante de Pago -->
        @if($compra->comprobante_pago)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Comprobante de Pago</h2>
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Comprobante subido</p>
                            <p class="text-sm text-gray-600">{{ basename($compra->comprobante_pago) }}</p>
                        </div>
                        <a href="{{ asset('storage/' . $compra->comprobante_pago) }}" target="_blank" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                            Descargar
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Sección de Acciones de Pago -->
        @php
            $credential = $compra->payment_credential;
        @endphp
        @if($compra->pago_estado === 'pendiente' && $compra->paymentMethod)
            <!-- Opción de Cambiar Método de Pago -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">🔄 ¿Quieres cambiar el método de pago?</h3>
                <p class="text-blue-800 mb-4">Puedes seleccionar un método de pago diferente antes de completar tu pago.</p>
                <button onclick="abrirModalCambiarMetodo()" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    🔄 Cambiar Método de Pago
                </button>
            </div>
            
            {{-- Ocultar MercadoPago (id=7) y Binance Pay (id=6) --}}
            @if(!in_array($compra->paymentMethod->id, [6, 7]))
            @if($compra->paymentMethod->slug == 'paypal')
                <!-- Métodos de Pago en Línea -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">💳 Completar Pago - {{ $compra->paymentMethod->nombre }}</h3>
                    <p class="text-green-800 mb-4">Tu pago aún está pendiente. Complétalo de forma segura:</p>
                    <button onclick="irAPagar()" class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                        💳 Pagar Ahora - ${{ number_format($compra->total, 2) }}
                    </button>
                    <p class="text-xs text-green-700 mt-2">Serás redirigido al portal seguro de {{ $compra->paymentMethod->nombre }}</p>
                </div>
            @elseif($compra->paymentMethod->slug == 'transferencia_bancaria')
                <!-- Método de Transferencia Bancaria -->
                @if($credential)
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">📋 Transferencia Bancaria</h3>
                        <p class="text-purple-800 mb-4">Tienes dos opciones para completar tu pago:</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button onclick="abrirModalDatos()" class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                                👁️ Ver Datos Bancarios
                            </button>
                            <button onclick="abrirModalComprobante()" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
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
        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
            <h3 class="text-lg font-bold text-gray-900 mb-2">¿Necesitas ayuda?</h3>
            <p class="text-gray-700 mb-4">
                Si tienes dudas sobre tu compra o pago, no dudes en contactarnos. Estamos aquí para ayudarte.
            </p>
            <a href="{{ route('landing.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Ir a Inicio →
            </a>
        </div>
    </div>
</div>

<script>
    function irAPagar() {
        const metodo = '{{ $compra->paymentMethod->nombre ?? '' }}';
        const monto = {{ $compra->total }};
        const compraId = {{ $compra->id }};

        // URLs de prueba para cada proveedor
        if (metodo.includes('PayPal')) {
            window.location.href = `https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sb-test@paypal.com&item_name=Compra%20Rifa%20%23${compraId}&amount=${monto}&currency_code=USD&invoice=${compraId}`;
        } else if (metodo.includes('MercadoPago')) {
            window.location.href = `/compra/${compraId}/pagar-mercadopago`;
        } else if (metodo.includes('Binance')) {
            window.location.href = `https://pay.binance.com/web/testpay?orderId=order_${compraId}`;
        }
    }

    function abrirModalDatos() {
        document.getElementById('modalDatos').classList.remove('hidden');
    }

    function cerrarModalDatos() {
        document.getElementById('modalDatos').classList.add('hidden');
    }

    function abrirModalComprobante() {
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

    // Funciones para Modal Cambiar Método
    function abrirModalCambiarMetodo() {
        const modal = document.getElementById('modalCambiarMetodo');
        modal.classList.remove('hidden');
    }

    function cerrarModalCambiarMetodo() {
        const modal = document.getElementById('modalCambiarMetodo');
        modal.classList.add('hidden');
    }

    // Cerrar con Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalCambiarMetodo();
        }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalCambiarMetodo')?.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalCambiarMetodo();
        }
    });
</script>

<!-- Modal Cambiar Método de Pago -->
<div id="modalCambiarMetodo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Cambiar Método de Pago</h2>
            <button onclick="cerrarModalCambiarMetodo()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="formCambiarMetodo" method="POST" action="{{ route('pagos.cambiar-metodo', $compra) }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona un nuevo método de pago</label>
                <select name="payment_method_id" id="selectMetodo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccionar método...</option>
                    @foreach(\App\Models\PaymentMethod::whereNotIn('id', [6, 7])->get() as $method)
                        <option value="{{ $method->id }}" @selected($compra->payment_method_id == $method->id)>
                            {{ $method->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="cerrarModalCambiarMetodo()" class="px-6 py-2 bg-gray-300 text-gray-900 rounded-lg font-semibold hover:bg-gray-400 transition">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Cambiar Método
                </button>
            </div>
        </form>
    </div>
</div>
