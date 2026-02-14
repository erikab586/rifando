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
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->tinyInteger('adicional')->default(0);
            $table->foreignId('subrifa_id')->nullable()->constrained('rifas')->onDelete('cascade');
            $table->foreignId('rifa_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            
            $table->index(['rifa_id', 'status']);
            $table->index('numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};
