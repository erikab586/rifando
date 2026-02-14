<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rifas Sistema') - Suerte y Fortuna</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --gold: #FFD700;
            --dark-gold: #DAA520;
            --purple: #7C3AED;
            --dark-purple: #6D28D9;
            --silver: #E8E8E8;
            --dark: #1F2937;
            --light: #F9FAFB;
        }
        
        body {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 pt-20">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-lg">♦</span>
                    </div>
                    <a href="{{ url('/') }}" class="text-white font-bold text-xl hover:text-yellow-300 transition">
                        Rifas
                    </a>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-200 hover:text-yellow-300 transition font-medium">
                        Inicio
                    </a>
                    
                    <a href="{{ route('pago.verificar') }}" class="text-gray-200 hover:text-yellow-300 transition font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verificar Pago
                    </a>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ url('/admin') }}" class="text-gray-200 hover:text-yellow-300 transition font-medium">
                                Admin
                            </a>
                        @endif
                        
                        <div class="relative group">
                            <button class="text-gray-200 hover:text-yellow-300 transition font-medium flex items-center space-x-2">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ url('/login') }}" class="text-gray-200 hover:text-yellow-300 transition font-medium">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>

                <!-- Menu Mobile -->
                <div class="md:hidden">
                    @auth
                        <button id="mobile-menu-btn" class="text-white hover:text-yellow-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
                <a href="/" class="block text-gray-200 hover:text-yellow-300 transition font-medium px-3 py-2">
                    Inicio
                </a>
                <a href="{{ route('pago.verificar') }}" class="block text-gray-200 hover:text-yellow-300 transition font-medium px-3 py-2">
                    Verificar Pago
                </a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="/admin" class="block text-gray-200 hover:text-yellow-300 transition font-medium px-3 py-2">
                            Admin
                        </a>
                    @endif
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left text-gray-200 hover:text-yellow-300 transition font-medium px-3 py-2">
                            Cerrar Sesión
                        </button>
                    </form>
                @else
                    <a href="/login" class="block text-gray-200 hover:text-yellow-300 transition font-medium px-3 py-2">
                        Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-700 font-medium">Errores encontrados:</p>
                        <ul class="text-red-600 text-sm mt-2 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-r-lg animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                            <span class="text-purple-900 font-bold">♦</span>
                        </div>
                        <h3 class="font-bold text-lg">Rifas</h3>
                    </div>
                    <p class="text-gray-300 text-sm">
                        Tu plataforma de sorteos de confianza con la mejor experiencia de juego.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="font-bold mb-4">Links</h4>
                    <ul class="text-gray-300 text-sm space-y-2">
                        <li><a href="{{ url('/') }}" class="hover:text-yellow-300 transition">Inicio</a></li>
                        <li><button onclick="openContactModal()" class="hover:text-yellow-300 transition cursor-pointer">Contacto</button></li>
                        <li><button onclick="openTermsModal()" class="hover:text-yellow-300 transition cursor-pointer">Términos</button></li>
                    </ul>
                </div>

                <!-- Social -->
                <div>
                    <h4 class="font-bold mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-yellow-300 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-yellow-300 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4">Contacto</h4>
                    <p class="text-gray-300 text-sm mb-2">📧 info@rifas.com</p>
                    <p class="text-gray-300 text-sm mb-2">📱 +55 51 98129-6129</p>
                    <p class="text-gray-300 text-sm">💬 WhatsApp 24/7</p>
                </div>
            </div>

            <div class="border-t border-purple-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">© 2025 Rifas Sistema. Todos los derechos reservados.</p>
                    <p class="text-gray-400 text-sm mt-4 md:mt-0">Hecho con ❤️ y un toque de suerte</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/5551981296129?text=Hola%20rifas%20quiero%20obtener%20mas%20informaci%C3%B3n" target="_blank" class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-lg transition transform hover:scale-110 z-40 flex items-center justify-center" title="Contactar por WhatsApp">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.946 1.347l-.355.213-3.671-.964.984 3.202-.214.336a9.87 9.87 0 001.51 4.853c.44.694 1.088 1.355 1.868 1.872 1.168.706 2.523 1.078 3.728 1.078 2.048 0 3.98-.778 5.41-2.196 1.43-1.419 2.223-3.31 2.223-5.393 0-1.266-.26-2.503-.734-3.667-.206-.525-.505-1.02-.878-1.464-.371-.443-.8-.827-1.268-1.125a9.87 9.87 0 00-3.743-.787zM12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0z"/>
        </svg>
    </a>

    <script>
    <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-96 overflow-y-auto">
            <div class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white p-6 flex justify-between items-center">
                <h2 class="text-xl font-bold">Contacto</h2>
                <button onclick="closeContactModal()" class="text-white hover:bg-purple-700 p-1 rounded transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Email</p>
                        <p class="text-gray-900 font-bold">info@rifas.com</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Teléfono/WhatsApp</p>
                        <p class="text-gray-900 font-bold">+55 51 98129-6129</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path fill="white" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Ubicación</p>
                        <p class="text-gray-900 font-bold">Venezuela</p>
                    </div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg mt-4">
                    <p class="text-gray-700 text-sm font-semibold mb-2">WhatsApp 24/7</p>
                    <a href="https://wa.me/5551981296129" target="_blank" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition">
                        💬 Contactar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Términos y Condiciones -->
    <div id="termsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white p-6 flex justify-between items-center sticky top-0">
                <h2 class="text-xl font-bold">Términos y Condiciones</h2>
                <button onclick="closeTermsModal()" class="text-white hover:bg-purple-700 p-1 rounded transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-4 text-sm text-gray-700">
                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">1. Aceptación de Términos</h3>
                    <p>Al usar nuestro servicio, aceptas estos términos y condiciones. Si no estás de acuerdo, por favor no uses la plataforma.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">2. Edad Mínima</h3>
                    <p>Debes ser mayor de 18 años para participar en nuestras rifas. Los menores de edad no pueden crear cuentas ni realizar compras.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">3. Responsabilidad del Usuario</h3>
                    <p>Eres responsable de mantener la confidencialidad de tu cuenta y contraseña. Todas las actividades realizadas bajo tu cuenta son tu responsabilidad.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">4. Política de Pago</h3>
                    <p>Los pagos deben realizarse antes de la finalización de la rifa. Aceptamos múltiples métodos de pago seguros. Revisa nuestra política de reembolso para más detalles.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">5. Premios y Sorteos</h3>
                    <p>Los ganadores se seleccionan de forma aleatoria. Los premios se otorgan según los términos específicos de cada rifa. No nos responsabilizamos por cambios de premios por parte de los patrocinadores.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">6. Limitación de Responsabilidad</h3>
                    <p>Nuestro servicio se proporciona "tal cual". No nos responsabilizamos por daños indirectos, incidentales o consecuentes derivados del uso de nuestra plataforma.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">7. Cambios en los Términos</h3>
                    <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios entrarán en vigencia inmediatamente. El uso continuado implica aceptación de los nuevos términos.</p>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">8. Contacto</h3>
                    <p>Si tienes preguntas sobre estos términos, contáctanos en info@rifas.com o al +55 51 98129-6129 vía WhatsApp.</p>
                </section>

                <div class="bg-purple-50 p-4 rounded-lg mt-4 border-t pt-4">
                    <p class="text-gray-600 text-xs">Última actualización: {{ now()->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Contact Modal
        function openContactModal() {
            document.getElementById('contactModal').classList.remove('hidden');
        }
        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
        }

        // Terms Modal
        function openTermsModal() {
            document.getElementById('termsModal').classList.remove('hidden');
        }
        function closeTermsModal() {
            document.getElementById('termsModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('contactModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeContactModal();
            }
        });

        document.getElementById('termsModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTermsModal();
            }
        });

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
