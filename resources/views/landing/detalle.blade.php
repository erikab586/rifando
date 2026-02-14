@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                @if($rifa->img)
                    <img src="{{ asset('storage/' . $rifa->img) }}" alt="{{ $rifa->name }}" class="w-full h-96 object-cover rounded-lg mb-6">
                @endif

                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $rifa->name }}</h1>
                
                <div class="prose max-w-none mb-8">
                    {{ $rifa->description }}
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-6 rounded-lg mb-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $estadisticas['total'] }}</div>
                        <div class="text-gray-600">Total de Boletos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $estadisticas['disponibles'] }}</div>
                        <div class="text-gray-600">Disponibles</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ $estadisticas['vendidos'] }}</div>
                        <div class="text-gray-600">Vendidos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $estadisticas['porcentaje_vendido'] }}%</div>
                        <div class="text-gray-600">Avance</div>
                    </div>
                </div>

                <!-- Barra de Progreso -->
                <div class="mb-8">
                    <div class="flex justify-between text-sm font-semibold mb-2 text-gray-700">
                        <span>Progreso de Venta</span>
                        <span>{{ $estadisticas['porcentaje_vendido'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-4">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-4 rounded-full transition-all duration-300"
                             style="width: {{ $estadisticas['porcentaje_vendido'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Grid de Boletos -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Boletos Disponibles</h2>
                    <div class="flex gap-3">
                        <input type="number" id="cantidad-azar" min="1" max="{{ $estadisticas['disponibles'] }}" placeholder="Cantidad" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        <button type="button" onclick="seleccionarAlAzar()" class="bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                            🎲 Al azar
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-5 md:grid-cols-8 gap-2 mb-6">
                    @forelse($boletos as $boleto)
                        <button type="button" class="boleto-btn aspect-square flex items-center justify-center rounded-lg font-bold text-sm bg-green-500 text-white hover:bg-green-600 transition"
                                data-id="{{ $boleto->id }}"
                                data-numero="{{ $boleto->numero }}">
                            {{ $boleto->numero }}
                        </button>
                    @empty
                        <p class="col-span-full text-gray-500 text-center py-8">No hay boletos disponibles</p>
                    @endforelse
                </div>

                <!-- Paginación de boletos -->
                <div class="flex justify-center mt-4">
                    {{ $boletos->links() }}
                </div>
            </div>
        </div>

        <!-- Sidebar - Compra -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg shadow-lg p-8 sticky top-8">
                <h3 class="text-2xl font-bold text-white mb-6">Carrito de Compra</h3>

                <div id="carrito-boletos" class="bg-white bg-opacity-20 rounded-lg p-4 mb-6 min-h-32">
                    <div class="text-white text-center py-8">
                        <p class="text-sm">Selecciona boletos para comenzar</p>
                    </div>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-white">
                        <span>Cantidad:</span>
                        <span id="cantidad-boletos">0</span>
                    </div>
                    <div class="flex justify-between text-white">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-white text-xl font-bold pt-4 border-t border-white border-opacity-30">
                        <span>Total:</span>
                        <span id="total">${{ number_format($rifa->amount, 2) }}</span>
                    </div>
                </div>

                @if($estadisticas['disponibles'] > 0)
                    <button id="btn-comprar" class="w-full bg-white text-blue-600 py-3 rounded-lg font-bold hover:bg-gray-100 transition mb-4" 
                            onclick="irACompra()">
                        Continuar Compra
                    </button>
                @else
                    <button class="w-full bg-gray-400 text-white py-3 rounded-lg font-bold cursor-not-allowed" disabled>
                        Sin Boletos Disponibles
                    </button>
                @endif

                <button id="btn-limpiar" class="w-full bg-red-500 text-white py-2 rounded-lg font-semibold hover:bg-red-600 transition" 
                        onclick="limpiarCarrito()">
                    Limpiar
                </button>
            </div>
        </div>
    </div>
</div>

<form id="form-compra" action="{{ route('landing.comprar', $rifa->slug) }}" method="GET" style="display: none;">
    <input type="hidden" id="boletos-ids" name="boletos_ids" value="">
</form>

<script>
    let carrito = [];
    const precioUnitario = {{ $rifa->amount }};

    document.querySelectorAll('.boleto-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const numero = this.dataset.numero;

            if (carrito.find(b => b.id === id)) {
                carrito = carrito.filter(b => b.id !== id);
                this.classList.remove('bg-blue-500');
                this.classList.add('bg-green-500');
            } else {
                carrito.push({ id, numero });
                this.classList.remove('bg-green-500');
                this.classList.add('bg-blue-500');
            }

            actualizarCarrito();
        });
    });

    function actualizarCarrito() {
        const cantidad = carrito.length;
        const subtotal = cantidad * precioUnitario;

        document.getElementById('cantidad-boletos').textContent = cantidad;
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('total').textContent = '$' + subtotal.toFixed(2);

        if (cantidad > 0) {
            const boletosHtml = carrito.map(b => 
                `<div class="flex justify-between text-white text-sm py-1">
                    <span>Boleto #${b.numero}</span>
                    <span>$${precioUnitario.toFixed(2)}</span>
                </div>`
            ).join('');
            document.getElementById('carrito-boletos').innerHTML = boletosHtml;
        } else {
            document.getElementById('carrito-boletos').innerHTML = 
                '<div class="text-white text-center py-8"><p class="text-sm">Selecciona boletos para comenzar</p></div>';
        }
    }

    function irACompra() {
        if (carrito.length === 0) {
            alert('Selecciona al menos un boleto');
            return;
        }
        const ids = carrito.map(b => b.id).join(',');
        document.getElementById('boletos-ids').value = ids;
        document.getElementById('form-compra').submit();
    }

    function limpiarCarrito() {
        carrito = [];
        document.querySelectorAll('.boleto-btn').forEach(btn => {
            btn.classList.remove('bg-blue-500');
            btn.classList.add('bg-green-500');
        });
        actualizarCarrito();
    }

    function seleccionarAlAzar() {
        const cantidadInput = document.getElementById('cantidad-azar');
        const cantidad = parseInt(cantidadInput.value);

        // Validar entrada
        if (!cantidad || cantidad < 1) {
            alert('Ingresa una cantidad válida');
            return;
        }

        // Obtener boletos disponibles (los que no están en el carrito)
        const botonesDisponibles = Array.from(document.querySelectorAll('.boleto-btn')).filter(btn => {
            const id = btn.dataset.id;
            return !carrito.find(b => b.id === id);
        });

        if (botonesDisponibles.length === 0) {
            alert('No hay boletos disponibles para seleccionar');
            return;
        }

        if (cantidad > botonesDisponibles.length) {
            alert(`Solo hay ${botonesDisponibles.length} boletos disponibles`);
            return;
        }

        // Seleccionar al azar
        const seleccionados = [];
        const copiaDisponibles = [...botonesDisponibles];

        for (let i = 0; i < cantidad; i++) {
            const indiceAzar = Math.floor(Math.random() * copiaDisponibles.length);
            const botonAzar = copiaDisponibles[indiceAzar];
            
            const id = botonAzar.dataset.id;
            const numero = botonAzar.dataset.numero;
            
            carrito.push({ id, numero });
            botonAzar.classList.remove('bg-green-500');
            botonAzar.classList.add('bg-blue-500');
            
            // Remover de la copia para no seleccionar el mismo dos veces
            copiaDisponibles.splice(indiceAzar, 1);
        }

        actualizarCarrito();
        cantidadInput.value = '';
    }
</script>
@endsection
