@extends('layouts.admin')

@section('title', 'Gestionar Roles')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestionar Roles</h1>
            <p class="text-gray-600 mt-2">Administra los roles y permisos del sistema</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
            + Nuevo Rol
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

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $role->name }}</h3>
                        <p class="text-sm text-purple-600 font-semibold">{{ $role->slug }}</p>
                    </div>
                    @if(!in_array($role->slug, ['admin', 'vendedor']))
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-semibold">Personalizado</span>
                    @else
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">Sistema</span>
                    @endif
                </div>

                @if($role->description)
                    <p class="text-gray-600 text-sm mb-4">{{ $role->description }}</p>
                @endif

                <div class="mb-4 pb-4 border-b">
                    <p class="text-sm font-semibold text-gray-900">
                        <span class="text-lg font-bold text-purple-600">{{ $role->users_count }}</span> usuario(s)
                    </p>
                </div>

                @if($role->permissions && count($role->permissions) > 0)
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-600 mb-2 uppercase">Permisos:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($role->permissions as $perm)
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $perm }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-2 pt-4 border-t">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="flex-1 text-center text-indigo-600 hover:text-indigo-900 font-semibold py-2 hover:bg-indigo-50 rounded transition">
                        Editar
                    </a>
                    @if(!in_array($role->slug, ['admin', 'vendedor']))
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" style="flex: 1;" onsubmit="return confirm('¿Estás seguro?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-red-600 hover:text-red-900 font-semibold py-2 hover:bg-red-50 rounded transition">
                                Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $roles->links() }}
    </div>
</div>
@endsection
