<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->cascadeOnDelete();
            $table->string('nombre'); // Ej: "PayPal Principal", "MercadoPago Ventas"
            $table->string('clave')->nullable(); // API Key, Token, etc
            $table->string('secreto')->nullable(); // Secret Key, Access Token, etc
            $table->json('datos_adicionales')->nullable(); // Para almacenar datos extra en JSON
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_credentials');
    }
};
