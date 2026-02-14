@extends('layouts.admin')

@section('title', 'Crear Rol')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Rol</h1>
        <p class="text-gray-600 mt-2">Define un nuevo rol personalizado con sus permisos</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <!-- Nombre -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Nombre del Rol
                </label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" value="{{ old('name') }}" placeholder="ej: Moderador" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div class="mb-6">
                <label for="slug" class="block text-sm font-semibold text-gray-900 mb-2">
                    Slug (identificador único)
                </label>
                <input type="text" name="slug" id="slug" class="w-full px-4 py-2 border rounded-lg @error('slug') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" value="{{ old('slug') }}" placeholder="ej: moderador" required>
                @error('slug')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                    Descripción
                </label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" placeholder="Descripción breve del rol">{{ old('description') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <a href="{{ route('admin.roles.index') }}" class="px-6 py-2 bg-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
                    Crear Rol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
