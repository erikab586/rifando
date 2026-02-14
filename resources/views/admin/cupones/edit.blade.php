@extends('layouts.admin')

@section('title', 'Editar Cupón')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Editar Cupón</h1>
        <p class="text-gray-600 mt-2">Actualiza los datos del cupón</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.cupones.update', $cupon) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Código -->
            <div class="mb-6">
                <label for="codigo" class="block text-sm font-semibold text-gray-900 mb-2">
                    Código del Cupón
                </label>
                <input type="text" name="codigo" id="codigo" class="w-full px-4 py-2 border rounded-lg @error('codigo') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" value="{{ old('codigo', $cupon->codigo) }}" required>
                @error('codigo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descuento -->
            <div class="mb-6">
                <label for="descuento" class="block text-sm font-semibold text-gray-900 mb-2">
                    Descuento (en $)
                </label>
                <input type="number" name="descuento" id="descuento" step="0.01" min="0.01" class="w-full px-4 py-2 border rounded-lg @error('descuento') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" value="{{ old('descuento', $cupon->descuento) }}" required>
                @error('descuento')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mínimo de Compra -->
            <div class="mb-6">
                <label for="minimo_compra" class="block text-sm font-semibold text-gray-900 mb-2">
                    Compra Mínima (opcional)
                </label>
                <input type="number" name="minimo_compra" id="minimo_compra" step="0.01" min="0" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" value="{{ old('minimo_compra', $cupon->minimo_compra) }}">
                @error('minimo_compra')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Válido Desde -->
                <div>
                    <label for="vigente_desde" class="block text-sm font-semibold text-gray-900 mb-2">
                        Válido Desde (opcional)
                    </label>
                    <input type="date" name="vigente_desde" id="vigente_desde" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" value="{{ old('vigente_desde', $cupon->vigente_desde?->format('Y-m-d')) }}">
                    @error('vigente_desde')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Válido Hasta -->
                <div>
                    <label for="vigente_hasta" class="block text-sm font-semibold text-gray-900 mb-2">
                        Válido Hasta (opcional)
                    </label>
                    <input type="date" name="vigente_hasta" id="vigente_hasta" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" value="{{ old('vigente_hasta', $cupon->vigente_hasta?->format('Y-m-d')) }}">
                    @error('vigente_hasta')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Uso Máximo -->
            <div class="mb-6">
                <label for="uso_maximo" class="block text-sm font-semibold text-gray-900 mb-2">
                    Máximo de Usos (opcional)
                </label>
                <input type="number" name="uso_maximo" id="uso_maximo" min="1" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" value="{{ old('uso_maximo', $cupon->uso_maximo) }}">
                @error('uso_maximo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Usos Actuales (solo lectura) -->
            <div class="mb-6 p-4 bg-gray-100 rounded-lg">
                <p class="text-sm font-semibold text-gray-900">Usos Actuales: <span class="text-lg text-purple-600">{{ $cupon->usos_actuales }}</span></p>
            </div>

            <!-- Estado -->
            <div class="mb-6">
                <label for="estado" class="block text-sm font-semibold text-gray-900 mb-2">
                    Estado
                </label>
                <select name="estado" id="estado" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" required>
                    <option value="activo" @if(old('estado', $cupon->estado) === 'activo') selected @endif>Activo</option>
                    <option value="inactivo" @if(old('estado', $cupon->estado) === 'inactivo') selected @endif>Inactivo</option>
                </select>
                @error('estado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <a href="{{ route('admin.cupones.index') }}" class="px-6 py-2 bg-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
                    Actualizar Cupón
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
