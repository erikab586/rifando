@extends('layouts.app')

@section('title', 'Rifas - Gana Increíbles Premios')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-gradient-to-b from-purple-900 via-indigo-900 to-blue-900 py-20 md:py-32">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-yellow-400 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full opacity-10 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                ¡Tu Suerte <span class="text-yellow-400">Empieza Aquí</span>!
            </h1>
            <p class="text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
               Únete a nuestras rifas y ten la oportunidad de ganar increíbles premios con boletos accesibles y procesos 100% transparentes. ¡Tu próximo premio puede estar a un clic!
            </p>
            <a href="#rifas" class="inline-block bg-gradient-to-r from-yellow-400 to-yellow-500 text-purple-900 font-bold px-8 py-4 rounded-lg hover:shadow-2xl hover:scale-105 transition transform">
                Ver Rifas Disponibles
            </a>
        </div>
    </div>
</div>

<!-- Rifas Section -->
<section id="rifas" class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Rifas Disponibles</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Elige tu rifa y participa ahora. Cada boleto es una oportunidad de ganar.</p>
        </div>

        @if($rifas->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rifas as $rifa)
                    <div class="group bg-white rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden hover:scale-105">
                        <!-- Image -->
                        <div class="relative h-48 md:h-56 bg-gradient-to-br from-purple-400 to-indigo-600 overflow-hidden">
                            @if($rifa->img)
                                <img src="{{ asset('storage/' . $rifa->img) }}" alt="{{ $rifa->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-6xl">
                                    ♦
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-yellow-400 text-purple-900 px-4 py-2 rounded-full font-bold text-sm shadow-lg">
                                $ {{ number_format($rifa->amount, 2) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $rifa->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $rifa->description }}</p>

                            <!-- Progress -->
                            <div class="mb-4">
                                @php
                                    $vendidos = $rifa->boletos()->where('status', 2)->count();
                                    $total = $rifa->num_boletos;
                                    $porcentaje = $total > 0 ? ($vendidos / $total) * 100 : 0;
                                @endphp
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>{{ $vendidos }} / {{ $total }} vendidos</span>
                                    <span>{{ round($porcentaje) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-2 rounded-full transition-all" style="width: {{ $porcentaje }}%"></div>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="text-sm text-gray-500 mb-6 space-y-1">
                                <p>📅 Hasta: {{ $rifa->end->format('d/m/Y H:i') }}</p>
                                <p class="font-semibold text-purple-600">
                                    {{ $rifa->boletos()->where('status', 0)->count() }} boletos disponibles
                                </p>
                            </div>

                            <!-- Button -->
                            <a href="{{ route('landing.detalle', $rifa) }}" class="block w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition text-center">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $rifas->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🎰</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay rifas disponibles</h3>
                <p class="text-gray-600">Vuelve pronto para nuevas oportunidades de ganar.</p>
            </div>
        @endif
    </div>
</section>
@endsection
