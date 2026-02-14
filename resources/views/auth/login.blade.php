<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Rifas</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900 min-h-screen flex items-center justify-center p-4">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-400 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full opacity-10 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-3 mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-2xl">♦</span>
                </div>
                <h1 class="text-3xl font-bold text-white">Rifas</h1>
            </div>
            <p class="text-gray-300 text-lg">Bienvenido de Vuelta</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10">
            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input 
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-600 transition"
                        placeholder="tu@email.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
                    <input 
                        type="password"
                        name="password"
                        id="password"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-600 transition"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-6 flex items-center">
                    <input 
                        type="checkbox"
                        name="remember"
                        id="remember"
                        class="w-4 h-4 text-purple-600 rounded"
                    >
                    <label for="remember" class="ml-2 text-gray-700 font-medium">Recuérdame</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition transform hover:scale-105">
                    Iniciar Sesión
                </button>

                <!-- Links -->
                <div class="mt-6 text-center space-y-2 text-sm">
                    <p>
                        <a href="/" class="text-purple-600 hover:text-purple-800 font-medium">← Volver al inicio</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-8 bg-white bg-opacity-10 backdrop-blur p-6 rounded-xl text-white text-center">
            <p class="text-sm font-medium mb-2">Credenciales de Prueba</p>
            <p class="text-xs text-gray-300">Email: <strong>admin@example.com</strong></p>
            <p class="text-xs text-gray-300">Contraseña: <strong>password</strong></p>
        </div>
    </div>
</body>
</html>
