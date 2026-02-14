@extends('layouts.admin')

@section('page-title', 'Gestionar Rifas')
@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Administración de Pagos</h1>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 p-4 rounded-lg mb-6">
            <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <form action="{{ route('admin.pagos.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filtro por Rifa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rifa</label>
                    <select name="rifa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas las rifas</option>
                        @foreach(\App\Models\Rifa::all() as $rifa)
                            <option value="{{ $rifa->id }}" @selected(request('rifa_id') == $rifa->id)>
                                {{ $rifa->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Estado de Pago -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Pago</label>
                    <select name="pago_estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pendiente" @selected(request('pago_estado') === 'pendiente')>Pendiente</option>
                        <option value="pagado" @selected(request('pago_estado') === 'pagado')>Pagado</option>
                        <option value="cancelado" @selected(request('pago_estado') === 'cancelado')>Cancelado</option>
                    </select>
                </div>

                <!-- Filtro por Método de Pago -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                    <select name="payment_method_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach(\App\Models\PaymentMethod::where('activo', 1)->get() as $method)
                            <option value="{{ $method->id }}" @selected(request('payment_method_id') == $method->id)>
                                {{ $method->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.pagos.index') }}" class="flex-1 px-6 py-2 bg-gray-300 text-gray-900 rounded-lg font-semibold hover:bg-gray-400 transition text-center">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Pagos -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Cliente</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Rifa</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Total</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Método</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Estado</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Comprobante</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compras as $compra)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $compra->cliente }} {{ $compra->apellido }}</div>
                            <div class="text-sm text-gray-600">{{ $compra->cedula }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $compra->rifa->name }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($compra->total, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($compra->paymentMethod)
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                    {{ $compra->paymentMethod->nombre }}
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">
                                    No especificado
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($compra->pago_estado === 'pagado')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Pagado</span>
                            @elseif($compra->pago_estado === 'cancelado')
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">Cancelado</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($compra->comprobante_pago)
                                <a href="{{ asset('storage/' . $compra->comprobante_pago) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-semibold">
                                    📄 Ver
                                </a>
                            @else
                                <span class="text-gray-500 text-sm">Sin comprobante</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 flex-wrap">
                                @if($compra->pago_estado !== 'pagado' && $compra->pago_estado !== 'cancelado')
                                    <form action="{{ route('admin.pagos.marcar-pagado', $compra) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition">
                                            ✓ Pagado
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pagos.marcar-cancelado', $compra) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition">
                                            ✕ Cancelar
                                        </button>
                                    </form>
                                    <button onclick="abrirModalCambiarMetodo({{ $compra->id }}, '{{ $compra->payment_method_id }}')" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition">
                                        🔄 Cambiar Método
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No hay pagos que mostrar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $compras->links() }}
    </div>
</div>

<!-- Modal Cambiar Método de Pago -->
<div id="modalCambiarMetodo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Cambiar Método de Pago</h2>
            <button onclick="cerrarModalCambiarMetodo()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="formCambiarMetodo" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nuevo Método de Pago</label>
                <select name="payment_method_id" id="selectMetodo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Seleccionar método...</option>
                    @foreach(\App\Models\PaymentMethod::where('activo', 1)->get() as $method)
                        <option value="{{ $method->id }}">{{ $method->nombre }}</option>
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

<script>
    let compraIdActual = null;

    function abrirModalCambiarMetodo(compraId, methodId) {
        compraIdActual = compraId;
        const modal = document.getElementById('modalCambiarMetodo');
        const form = document.getElementById('formCambiarMetodo');
        const select = document.getElementById('selectMetodo');
        
        // Establecer la acción del formulario
        form.action = `/admin/pagos/${compraId}/cambiar-metodo`;
        
        // Seleccionar el método actual
        select.value = methodId;
        
        modal.classList.remove('hidden');
    }

    function cerrarModalCambiarMetodo() {
        const modal = document.getElementById('modalCambiarMetodo');
        modal.classList.add('hidden');
        compraIdActual = null;
    }

    // Cerrar modal con Escape
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
@endsection
