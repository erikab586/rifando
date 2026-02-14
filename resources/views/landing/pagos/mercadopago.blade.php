@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('pago.detalle', $compra) }}" class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Atrás
            </a>
        </div>

        @if(isset($error))
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                <p class="text-red-800 font-semibold">⚠️ {{ $error }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Pago con MercadoPago</h1>
            </div>

            @if(!isset($error))
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-8">
                    <p class="text-yellow-800 font-semibold mb-2">⏳ Procesando...</p>
                    <p class="text-yellow-700">Estamos preparando tu pago con MercadoPago. Por favor espera...</p>
                </div>

                <div class="text-center">
                    <div class="inline-block animate-spin">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="mt-4 text-gray-600">Si no eres redirigido automáticamente en 10 segundos, <a href="javascript:window.history.back()" class="text-blue-600 hover:underline">haz clic aquí</a></p>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <h2 class="text-lg font-bold text-blue-900 mb-4">📋 Instrucciones de Configuración</h2>
                    <div class="space-y-4 text-blue-800 text-sm">
                        <p><strong>Para habilitar pagos con MercadoPago, sigue estos pasos:</strong></p>
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Crea una cuenta en <a href="https://www.mercadopago.com.ve" target="_blank" class="underline">MercadoPago.com.ve</a></li>
                            <li>Ve a tu panel de control y accede a "Credenciales API"</li>
                            <li>Copia tu <strong>Public Key</strong> y <strong>Access Token</strong></li>
                            <li>En el panel admin, ve a "Credenciales de Pago"</li>
                            <li>Crea o edita las credenciales de MercadoPago</li>
                            <li>Pega tus credenciales en los campos correspondientes</li>
                        </ol>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">💳 Resumen de Pago</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Compra #:</span>
                            <span class="font-semibold">{{ $compra->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rifa:</span>
                            <span class="font-semibold">{{ $compra->rifa->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Boletos:</span>
                            <span class="font-semibold">{{ $compra->boletos->count() }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="text-gray-900 font-semibold">Total a Pagar:</span>
                            <span class="text-2xl font-bold text-green-600">${{ number_format($compra->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('pago.detalle', $compra) }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-900 rounded-lg font-semibold hover:bg-gray-400 transition text-center">
                        Volver
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@if(!isset($error))
<script>
    // Auto-redirect después de 10 segundos en caso de error
    setTimeout(function() {
        console.log("Tiempo de espera agotado, regresando...");
        // No redirigir automáticamente si ya se procesó
    }, 10000);
</script>
@endif
@endsection
