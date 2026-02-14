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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->string('apellido');
            $table->string('cedula', 20);
            $table->string('telefono', 20);
            $table->string('email');
            $table->string('referencia')->nullable();
            $table->string('ref_link')->nullable();
            $table->foreignId('rifa_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('status')->default(0);
            $table->decimal('total', 10, 2);
            $table->string('mercadopago_id')->nullable();
            $table->timestamps();
            
            $table->index(['rifa_id', 'status']);
            $table->index('email');
            $table->index('cedula');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
