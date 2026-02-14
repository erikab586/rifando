<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RifaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CuponController;

use App\Http\Controllers\PaymentCredentialController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoPublicController;

// Authentication Routes

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Landing Page - Públicas
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/rifa/{rifa:slug}', [LandingController::class, 'detalle'])->name('landing.detalle');
Route::get('/rifa/{rifa:slug}/comprar', [LandingController::class, 'comprar'])->name('landing.comprar');
Route::post('/api/verificar-boleto', [LandingController::class, 'verificarBoleto'])->name('api.verificar-boleto');
Route::post('/compra/crear', [CompraController::class, 'crearCompra'])->name('compra.crear');
Route::get('/compra/{compra}/exitosa', [LandingController::class, 'exitosa'])->name('landing.compra.exitosa');
Route::get('/compra/{compra}/comprobante/descargar', [CompraController::class, 'descargarComprobante'])->name('compra.descargar-comprobante');
Route::get('/compra/{compra}/ticket/descargar', [CompraController::class, 'descargarTicket'])->name('compra.descargar-ticket');
Route::get('/compra/{compra}/ticket/ver', [CompraController::class, 'verTicket'])->name('compra.ver-ticket');
Route::post('/compra/{compra}/subir-comprobante', [CompraController::class, 'subirComprobante'])->name('compra.subir-comprobante');
Route::get('/compra/{compra}/pagar-mercadopago', [CompraController::class, 'pagarMercadoPago'])->name('compra.pagar-mercadopago');
Route::get('/compra/{compra}/mercadopago/success', [CompraController::class, 'mercadopagoSuccess'])->name('compra.mercadopago-success');
Route::get('/compra/{compra}/mercadopago/failure', [CompraController::class, 'mercadopagoFailure'])->name('compra.mercadopago-failure');
Route::get('/compra/{compra}/mercadopago/pending', [CompraController::class, 'mercadopagoPending'])->name('compra.mercadopago-pending');

// Rutas de Pago Públicas (Sin autenticación)
Route::get('/pago/verificar', [PagoPublicController::class, 'verificar'])->name('pago.verificar');
Route::post('/pago/buscar', [PagoPublicController::class, 'buscar'])->name('pago.buscar');
Route::get('/pago/{compra}/detalle', [PagoPublicController::class, 'detalle'])->name('pago.detalle');
Route::put('/pagos/{compra}/cambiar-metodo', [PagoPublicController::class, 'cambiarMetodo'])->name('pagos.cambiar-metodo');

// Admin - Rutas Protegidas
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Rifas - Solo admin
    Route::middleware('admin')->group(function () {
        Route::resource('rifas', RifaController::class);
    });

    // Compras - Todos autenticados
    Route::resource('compras', CompraController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('/compras/{compra}/confirmar-pago', [CompraController::class, 'confirmarPago'])->name('compras.confirmar-pago');

    // Usuarios - Solo admin
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles - Solo admin
    Route::middleware('admin')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Cupones - Solo admin
    Route::middleware('admin')->group(function () {
        Route::resource('cupones', CuponController::class);
    });

    // Credenciales de Pago - Solo admin
    Route::middleware('admin')->group(function () {
        Route::resource('payment-credentials', PaymentCredentialController::class);
    });

    // Administración de Pagos - Solo admin
    Route::middleware('admin')->group(function () {
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::post('/pagos/{compra}/marcar-pagado', [PagoController::class, 'marcarPagado'])->name('pagos.marcar-pagado');
        Route::post('/pagos/{compra}/marcar-cancelado', [PagoController::class, 'marcarCancelado'])->name('pagos.marcar-cancelado');
        Route::put('/pagos/{compra}/cambiar-metodo', [PagoController::class, 'cambiarMetodo'])->name('pagos.cambiar-metodo');
        Route::post('/pagos/{compra}/subir-comprobante', [PagoController::class, 'subirComprobante'])->name('pagos.subir-comprobante');
    });
});
