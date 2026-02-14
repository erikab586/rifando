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
        Schema::create('rifas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('img')->nullable();
            $table->integer('num_boletos');
            $table->integer('num_adicionales')->default(0);
            $table->decimal('amount', 10, 2);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->tinyInteger('status')->default(1);
            $table->integer('vista')->default(0);
            $table->decimal('bono', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index(['start', 'end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};
