@extends('layouts.admin')

@section('title', 'Gestionar Cupones')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestionar Cupones</h1>
            <p class="text-gray-600 mt-2">Administra todos los cupones de descuento</p>
        </div>
        <a href="{{ route('admin.cupones.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
            + Nuevo Cupón
        </a>
    </div>

    <!-- Messages -->
    @if($message = Session::get('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            <p class="font-semibold">{{ $message }}</p>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($cupones->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Código</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Descuento</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Válido Hasta</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Usos</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Estado</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($cupones as $cupon)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-purple-600">{{ $cupon->codigo }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-green-600">${{ number_format($cupon->descuento, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($cupon->vigente_hasta)
                                    {{ $cupon->vigente_hasta->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-500">Sin límite</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm bg-gray-100 px-3 py-1 rounded">
                                    {{ $cupon->usos_actuales }}
                                    @if($cupon->uso_maximo)
                                        / {{ $cupon->uso_maximo }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold @if($cupon->estado === 'activo') bg-green-100 text-green-700 @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($cupon->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.cupones.edit', $cupon) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</a>
                                    <form action="{{ route('admin.cupones.destroy', $cupon) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $cupones->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <div class="text-6xl mb-4">🎫</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay cupones</h3>
                <p class="text-gray-600">Crea tu primer cupón para comenzar</p>
            </div>
        @endif
    </div>
</div>
@endsection
