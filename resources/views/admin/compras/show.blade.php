@extends('layouts.admin')

@section('page-title', 'Detalle de Compra')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Detalles de Compra #{{ $compra->id }}</h1>
        <a href="{{ route('admin.compras.index') }}" class="text-gray-600 hover:text-gray-900">
            ← Volver
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del Cliente -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Información del Cliente</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Nombre</p>
                        <p class="font-bold">{{ $compra->cliente }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Apellido</p>
                        <p class="font-bold">{{ $compra->apellido }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Cédula</p>
                        <p class="font-bold">{{ $compra->cedula }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Teléfono</p>
                        <p class="font-bold">{{ $compra->telefono }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-bold">{{ $compra->email ?? 'No proporcionado' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Estado</p>
                        <p class="font-bold">{{ $compra->estado ?? 'No especificado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Información de la Compra -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Información de la Compra</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rifa:</span>
                        <span class="font-bold">{{ $compra->rifa->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fecha de Compra:</span>
                        <span class="font-bold">{{ $compra->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cantidad de Boletos:</span>
                        <span class="font-bold">{{ $compra->boletos()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Precio Unitario:</span>
                        <span class="font-bold">${{ number_format($compra->rifa->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-600 font-semibold">Total:</span>
                        <span class="font-bold text-lg text-green-600">${{ number_format($compra->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Boletos Asignados -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Boletos Asignados</h2>
                
                <div class="grid grid-cols-5 md:grid-cols-8 gap-2">
                    @foreach($compra->boletos as $boleto)
                        <div class="aspect-square bg-blue-500 text-white rounded-lg flex items-center justify-center font-bold text-sm">
                            {{ $boleto->numero }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar - Estado y Acciones -->
        <div class="space-y-6">
            <!-- Estado Actual -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Estado de Compra</h2>
                
                <div class="mb-6">
                    <span class="inline-block px-4 py-2 rounded-full font-bold text-lg
                        @if($compra->status === '0') bg-yellow-100 text-yellow-800
                        @elseif($compra->status === '1') bg-blue-100 text-blue-800
                        @elseif($compra->status === '2') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        @switch($compra->status)
                            @case('0') Pendiente @break
                            @case('1') Reservado @break
                            @case('2') Pagado @break
                            @case('3') Cancelado @break
                        @endswitch
                    </span>
                </div>

                <p class="text-gray-600 text-sm mb-4">
                    @switch($compra->status)
                        @case('0') La compra está pendiente de confirmación @break
                        @case('1') Los boletos están reservados, pendiente de pago @break
                        @case('2') La compra ha sido completada y pagada @break
                        @case('3') La compra ha sido cancelada @break
                    @endswitch
                </p>
            </div>

            <!-- Estado de Pago -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Estado de Pago</h2>
                
                <div class="mb-6">
                    @if($compra->pago_estado === 'pagado')
                        <span class="inline-block px-4 py-2 rounded-full font-bold text-lg bg-green-100 text-green-800">
                            ✓ Pagado
                        </span>
                    @elseif($compra->pago_estado === 'cancelado')
                        <span class="inline-block px-4 py-2 rounded-full font-bold text-lg bg-red-100 text-red-800">
                            ✕ Cancelado
                        </span>
                    @else
                        <span class="inline-block px-4 py-2 rounded-full font-bold text-lg bg-yellow-100 text-yellow-800">
                            ⏳ Pendiente
                        </span>
                    @endif
                </div>

                @if($compra->paymentMethod)
                    <div class="mb-4 text-sm">
                        <p class="text-gray-600">Método de Pago:</p>
                        <p class="font-bold">{{ $compra->paymentMethod->nombre }}</p>
                    </div>
                @endif

                @if($compra->comprobante_pago)
                    <div class="mb-4 text-sm">
                        <p class="text-gray-600">Comprobante:</p>
                        <a href="{{ asset('storage/' . $compra->comprobante_pago) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold">
                            📄 Ver Comprobante
                        </a>
                    </div>
                @endif

                @if($compra->pago_estado === 'pendiente')
                    <div class="space-y-2">
                        <form action="{{ route('admin.pagos.marcar-pagado', $compra) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                                ✓ Marcar como Pagado
                            </button>
                        </form>
                        <form action="{{ route('admin.pagos.marcar-cancelado', $compra) }}" method="POST" onsubmit="return confirm('¿Cancelar este pago?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                                ✕ Cancelar Pago
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Acciones</h2>
                
                <div class="space-y-2">
                    <a href="{{ route('admin.pagos.index') }}?rifa_id={{ $compra->rifa_id }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700 transition text-sm font-semibold">
                        💳 Ver Pagos de Esta Rifa
                    </a>
                    <a href="{{ route('landing.compra.exitosa', $compra) }}" target="_blank" class="block w-full px-4 py-2 bg-purple-600 text-white rounded-lg text-center hover:bg-purple-700 transition text-sm font-semibold">
                        📄 Ver Comprobante de Compra
                    </a>
                    
                    @if($compra->status !== '3')
                        <form action="{{ route('admin.compras.destroy', $compra) }}" method="POST" onsubmit="return confirm('¿Eliminar esta compra?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                                🗑️ Cancelar Compra
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-3">Información del Sistema</h2>
                
                <div class="space-y-2 text-xs text-gray-600">
                    <div>
                        <p class="font-semibold">Creada:</p>
                        <p>{{ $compra->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Última actualización:</p>
                        <p>{{ $compra->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
