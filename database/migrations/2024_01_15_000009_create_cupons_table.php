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
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->decimal('descuento', 10, 2);
            $table->decimal('minimo_compra', 10, 2)->nullable();
            $table->dateTime('vigente_desde')->nullable();
            $table->dateTime('vigente_hasta')->nullable();
            $table->integer('uso_maximo')->nullable();
            $table->integer('usos_actuales')->default(0);
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
