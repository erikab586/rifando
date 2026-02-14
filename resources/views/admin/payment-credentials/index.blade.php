@extends('layouts.admin')

@section('page-title', 'Gestionar Rifas')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Credenciales de Pago</h1>
        <a href="{{ route('admin.payment-credentials.create') }}" 
           class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
            + Agregar Credencial
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 p-4 rounded-lg mb-6">
            <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if ($credentials->isEmpty())
        <div class="bg-gray-50 border border-gray-200 p-8 rounded-lg text-center">
            <p class="text-gray-600 mb-4">No hay credenciales de pago registradas</p>
            <a href="{{ route('admin.payment-credentials.create') }}" 
               class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                Crear primera credencial
            </a>
        </div>
    @else
        <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Método de Pago</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nombre</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Clave API</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Estado</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($credentials as $credential)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded">
                                    {{ $credential->paymentMethod->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-900">{{ $credential->nombre }}</td>
                            <td class="px-6 py-4">
                                <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ substr($credential->clave, 0, 20) }}...</code>
                            </td>
                            <td class="px-6 py-4">
                                @if ($credential->activo)
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded">
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded">
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.payment-credentials.edit', $credential) }}" 
                                       class="px-3 py-2 bg-yellow-500 text-white rounded-lg text-sm font-semibold hover:bg-yellow-600 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.payment-credentials.destroy', $credential) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $credentials->links() }}
        </div>
    @endif
</div>
@endsection
