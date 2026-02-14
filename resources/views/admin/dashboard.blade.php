@extends('layouts.admin')

@section('page-title', 'Panel de Administración')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Panel de Administración</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card: Total de Rifas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Rifas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Rifa::count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card: Total de Compras -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Compras</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Compra::count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card: Ingresos Totales -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Ingresos Totales</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format(\App\Models\Compra::pagado()->sum('total'), 2) }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card: Boletos Vendidos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Boletos Vendidos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Boleto::vendido()->count() }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Últimas Compras -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Últimas Compras</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">#ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Cliente</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Rifa</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Total</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Estado</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse(\App\Models\Compra::latest()->take(5)->get() as $compra)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $compra->id }}</td>
                            <td class="px-6 py-4">{{ $compra->cliente }} {{ $compra->apellido }}</td>
                            <td class="px-6 py-4">{{ $compra->rifa->name }}</td>
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
                                <a href="{{ route('admin.compras.show', $compra) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No hay compras registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
