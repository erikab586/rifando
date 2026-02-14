@extends('layouts.admin')

@section('page-title', 'Gestionar Rifas')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Agregar Credencial de Pago</h1>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-6">
                    <p class="text-red-800 font-semibold mb-2">Errores encontrados:</p>
                    <ul class="text-red-700 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.payment-credentials.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Método de Pago -->
                <div>
                    <label for="payment_method_id" class="block text-sm font-medium text-gray-700 mb-2">Método de Pago *</label>
                    <select name="payment_method_id" id="payment_method_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Seleccionar método de pago</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}" @selected(old('payment_method_id') == $method->id)>
                                {{ $method->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Credencial *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ej: PayPal Principal, MercadoPago Ventas">
                    @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Clave API -->
                <div>
                    <label for="clave" class="block text-sm font-medium text-gray-700 mb-2">Clave API / Token *</label>
                    <input type="password" name="clave" id="clave" value="{{ old('clave') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ingresa tu clave API">
                    @error('clave') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Secret -->
                <div>
                    <label for="secreto" class="block text-sm font-medium text-gray-700 mb-2">Secret Key / Access Token</label>
                    <input type="password" name="secreto" id="secreto" value="{{ old('secreto') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ingresa tu secret key (opcional)">
                    @error('secreto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Datos Adicionales - Transferencia Bancaria -->
                <div id="datos-bancarios" style="display: none;" class="space-y-4 p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Datos Bancarios para Transferencia</h3>
                    
                    <div>
                        <label for="banco" class="block text-sm font-medium text-gray-700 mb-2">Banco</label>
                        <input type="text" name="banco" id="banco" value="{{ old('banco') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Banco de Venezuela">
                    </div>

                    <div>
                        <label for="cuenta" class="block text-sm font-medium text-gray-700 mb-2">Número de Cuenta</label>
                        <input type="text" name="cuenta" id="cuenta" value="{{ old('cuenta') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: 01234567890123">
                    </div>

                    <div>
                        <label for="cedula" class="block text-sm font-medium text-gray-700 mb-2">Cédula Titular</label>
                        <input type="text" name="cedula" id="cedula" value="{{ old('cedula') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: V-12345678">
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono de Contacto</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: +58 412 1234567">
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="activo" value="1" @checked(old('activo', true))
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Activo (disponible para pagos)</span>
                    </label>
                </div>

                <!-- Botones -->
                <div class="flex gap-4 pt-4">
                    <a href="{{ route('admin.payment-credentials.index') }}" 
                       class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition text-center">
                        Cancelar
                    </a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        Crear Credencial
                    </button>
                </div>
            </form>

            <script>
                const paymentMethodSelect = document.getElementById('payment_method_id');
                const datosBancariosDiv = document.getElementById('datos-bancarios');

                function toggleDatosBancarios() {
                    const selectedText = paymentMethodSelect.options[paymentMethodSelect.selectedIndex]?.text || '';
                    if (selectedText.includes('Transferencia')) {
                        datosBancariosDiv.style.display = 'block';
                    } else {
                        datosBancariosDiv.style.display = 'none';
                    }
                }

                paymentMethodSelect.addEventListener('change', toggleDatosBancarios);
                toggleDatosBancarios();
            </script>
@endsection