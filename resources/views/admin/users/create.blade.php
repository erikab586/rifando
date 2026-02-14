@extends('layouts.admin')

@section('title', 'Crear Usuario')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
        <p class="text-gray-600 mt-2">Completa el formulario para crear un usuario</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <!-- Nombre -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Nombre Completo
                </label>
                <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                    Email
                </label>
                <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                    Contraseña
                </label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg @error('password') border-red-500 @else border-gray-300 @enderror focus:outline-none focus:border-purple-500" required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                    Confirmar Contraseña
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-lg border-gray-300 focus:outline-none focus:border-purple-500" required>
            </div>

            <!-- Roles -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-3">
                    Asignar Roles
                </label>
                <div class="space-y-3">
                    @foreach($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->id }}" class="rounded border-gray-300" @if(in_array($role->id, old('roles', []))) checked @endif>
                            <label for="role_{{ $role->id }}" class="ml-3 font-semibold text-gray-900">
                                {{ $role->name }}
                            </label>
                            @if($role->description)
                                <span class="ml-2 text-gray-500 text-sm">- {{ $role->description }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                @error('roles')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
