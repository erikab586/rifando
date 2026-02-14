@extends('layouts.admin')

@section('page-title', 'Gestionar Compras')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestionar Compras</h1>
        <a href="{{ route('admin.pagos.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
            💳 Administrar Pagos
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">#ID</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Cliente</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Rifa</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Boletos</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Total</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Estado Compra</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Estado Pago</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($compras as $compra)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">{{ $compra->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold">{{ $compra->cliente }} {{ $compra->apellido }}</div>
                            <div class="text-xs text-gray-600">{{ $compra->telefono }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $compra->rifa->name }}</td>
                        <td class="px-6 py-4 center font-bold">{{ $compra->boletos()->count() }}</td>
                        <td class="px-6 py-4 font-semibold">${{ number_format($compra->total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
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
                        </td>
                        <td class="px-6 py-4">
                            @if($compra->pago_estado === 'pagado')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">✓ Pagado</span>
                            @elseif($compra->pago_estado === 'cancelado')
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">✕ Cancelado</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded">⏳ Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.compras.show', $compra) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Ver
                            </a>
                            @if($compra->pago_estado === 'pendiente')
                                <form action="{{ route('admin.pagos.marcar-pagado', $compra) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-semibold" title="Marcar pago como confirmado">
                                        ✓ Pagar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No hay compras registradas
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
@endsection
