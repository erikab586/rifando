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

        <h1 class="text-3xl font-bold text-gray-900 mb-8">
            Tus Compras y Pagos
        </h1>

        @if ($compras->isEmpty())
            <div class="bg-gray-50 border border-gray-200 p-8 rounded-lg text-center">
                <p class="text-gray-600 mb-4">No se encontraron compras</p>
                <a href="{{ route('landing.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Comprar Ahora
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($compras as $compra)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $compra->rifa->name }}</h2>
                                <p class="text-sm text-gray-600">Compra #{{ $compra->id }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900">${{ number_format($compra->total, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $compra->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <!-- Método de Pago -->
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 font-semibold">Método Pago</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    @if($compra->paymentMethod)
                                        {{ $compra->paymentMethod->nombre }}
                                    @else
                                        <span class="text-gray-500">No especificado</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Boletos -->
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 font-semibold">Boletos</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $compra->boletos->count() }} boletos</p>
                            </div>

                            <!-- Estado Pago -->
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-xs text-gray-600 font-semibold">Estado Pago</p>
                                <div class="mt-1">
                                    @if($compra->pago_estado === 'pagado')
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">✓ Pagado</span>
                                    @elseif($compra->pago_estado === 'cancelado')
                                        <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">✕ Cancelado</span>
                                    @else
                                        <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded">⏳ Pendiente</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <a href="{{ route('pago.detalle', $compra) }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                                Ver Detalles
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
