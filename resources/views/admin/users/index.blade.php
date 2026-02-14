@extends('layouts.admin')

@section('title', 'Gestionar Usuarios')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestionar Usuarios</h1>
            <p class="text-gray-600 mt-2">Administra todos los usuarios del sistema</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
            + Nuevo Usuario
        </a>
    </div>

    <!-- Messages -->
    @if($message = Session::get('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            <p class="font-semibold">{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            <p class="font-semibold">{{ $message }}</p>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($users->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Nombre</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Rol</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 flex-wrap">
                                    @forelse($user->roles as $role)
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full font-semibold">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500">Sin rol asignado</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $users->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No hay usuarios</h3>
                <p class="text-gray-600">Crea tu primer usuario para comenzar</p>
            </div>
        @endif
    </div>
</div>
@endsection
