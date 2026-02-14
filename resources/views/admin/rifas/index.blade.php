@extends('layouts.admin')

@section('page-title', 'Gestionar Rifas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestionar Rifas</h1>
        <a href="{{ route('admin.rifas.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
            + Nueva Rifa
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
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Nombre</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Boletos</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Precio</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Período</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Estado</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-900">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($rifas as $rifa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold">{{ $rifa->name }}</td>
                        <td class="px-6 py-4">
                            {{ $rifa->boletosDisponibles()->count() }}/{{ $rifa->boletos()->count() }}
                        </td>
                        <td class="px-6 py-4">${{ number_format($rifa->amount, 2) }}</td>
                        <td class="px-6 py-4 text-gray-600 text-xs">
                            {{ $rifa->start->format('d/m') }} - {{ $rifa->end->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                {{ $rifa->status === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $rifa->status === 1 ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('admin.rifas.show', $rifa) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Ver
                            </a>
                            @if($rifa->status === 1)
                                <span class="text-gray-400 font-semibold cursor-not-allowed" title="No puedes editar una rifa activa">
                                    Editar
                                </span>
                            @else
                                <a href="{{ route('admin.rifas.edit', $rifa) }}" class="text-orange-600 hover:text-orange-800 font-semibold">
                                    Editar
                                </a>
                            @endif
                            @if($rifa->status === 1)
                                <span class="text-gray-400 font-semibold cursor-not-allowed" title="No puedes eliminar una rifa activa">
                                    Eliminar
                                </span>
                            @elseif($rifa->boletos()->where('status', 2)->count() > 0)
                                <span class="text-gray-400 font-semibold cursor-not-allowed" title="No puedes eliminar una rifa con boletos vendidos">
                                    Eliminar
                                </span>
                            @else
                                <form action="{{ route('admin.rifas.destroy', $rifa) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No hay rifas registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $rifas->links() }}
    </div>
</div>
@endsection
