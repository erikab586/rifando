@extends('layouts.admin')

@section('page-title', 'Rifa: ' . $rifa->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $rifa->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.rifas.edit', $rifa) }}" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                Editar
            </a>
            <a href="{{ route('admin.rifas.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                ← Volver
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-600 text-sm">Total de Boletos</p>
            <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['total'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-600 text-sm">Boletos Disponibles</p>
            <p class="text-3xl font-bold text-green-600">{{ $estadisticas['disponibles'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-600 text-sm">Boletos Vendidos</p>
            <p class="text-3xl font-bold text-red-600">{{ $estadisticas['vendidos'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-600 text-sm">Ingresos</p>
            <p class="text-3xl font-bold text-purple-600">${{ number_format($estadisticas['ingresos'], 2) }}</p>
        </div>
    </div>

    <!-- Información de la Rifa -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Información General</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 text-sm">Descripción</p>
                    <p class="text-gray-900">{{ $rifa->description ?? 'Sin descripción' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Precio por Boleto</p>
                        <p class="text-lg font-bold">${{ number_format($rifa->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Boletos Adicionales</p>
                        <p class="text-lg font-bold">{{ $rifa->num_adicionales }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Fecha de Inicio</p>
                        <p class="text-lg font-bold">{{ $rifa->start->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Fecha de Fin</p>
                        <p class="text-lg font-bold">{{ $rifa->end->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Estado</p>
                        <p class="text-lg font-bold">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold
                                {{ $rifa->status === '1' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $rifa->status === '1' ? 'Activa' : 'Inactiva' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Creada por</p>
                        <p class="text-lg font-bold">{{ $rifa->user->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Progreso de Venta</h2>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm font-semibold mb-2">
                        <span>Avance</span>
                        <span>
                            @if($estadisticas['total'] > 0)
                                {{ round(($estadisticas['vendidos'] / $estadisticas['total']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-blue-500 h-4 rounded-full" 
                                style="width: {{ $estadisticas['total'] > 0 ? round(($estadisticas['vendidos'] / $estadisticas['total']) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600 text-sm">Porcentaje Vendido</p>
                    <p class="text-3xl font-bold text-blue-600">
                        @if($estadisticas['total'] > 0)
                            {{ round(($estadisticas['vendidos'] / $estadisticas['total']) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Boletos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Boletos</h2>
        
        <div class="grid grid-cols-6 md:grid-cols-10 gap-2 mb-6">
            @forelse($boletos as $boleto)
                <div class="aspect-square flex items-center justify-center rounded-lg font-bold text-sm
                    @if($boleto->status === '0') bg-green-500 text-white
                    @elseif($boleto->status === '1') bg-yellow-500 text-white
                    @elseif($boleto->status === '2') bg-blue-500 text-white
                    @else bg-red-500 text-white
                    @endif
                    cursor-help" title="@switch($boleto->status)
                        @case('0') Disponible @break
                        @case('1') Reservado @break
                        @case('2') Comprado @break
                        @case('3') Cancelado @break
                    @endswitch">
                    {{ $boleto->numero }}
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-8">No hay boletos</p>
            @endforelse
        </div>

        <div class="flex gap-4 mb-6 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span>Disponible</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                <span>Reservado</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                <span>Comprado</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span>Cancelado</span>
            </div>
        </div>

        {{ $boletos->links() }}
    </div>
</div>
@endsection
