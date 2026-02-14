@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="inline-block p-4 bg-blue-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Seguimiento de Pago</h1>
                <p class="text-gray-600">Consulta el estado de tu compra y pago</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-6">
                    <p class="text-red-800 font-semibold mb-2">Error:</p>
                    <ul class="text-red-700 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pago.buscar') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="cedula" class="block text-sm font-medium text-gray-700 mb-2">
                        Ingresa tu cédula de identidad *
                    </label>
                    <input type="text" name="cedula" id="cedula" value="{{ old('cedula') }}" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                           placeholder="Ej: 12345678">
                    <p class="text-sm text-gray-500 mt-2">
                        ℹ️ Usamos tu cédula para identificar tus compras y pagos
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-blue-800">
                            <strong>¿Primera vez?</strong> Dirígete a <a href="{{ route('landing.index') }}" class="underline hover:no-underline font-semibold">nuestras rifas</a> para realizar tu compra.
                        </p>
                    </div>
                </div>

                <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Buscar Compras
                </button>

                <a href="{{ route('landing.index') }}" class="block text-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Volver al Inicio
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
