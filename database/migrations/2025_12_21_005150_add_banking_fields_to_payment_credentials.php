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
        Schema::table('payment_credentials', function (Blueprint $table) {
            $table->string('banco')->nullable()->after('datos_adicionales');
            $table->string('cuenta')->nullable()->after('banco');
            $table->string('cedula')->nullable()->after('cuenta');
            $table->string('telefono')->nullable()->after('cedula');
        });
    }

    public function down(): void
    {
        Schema::table('payment_credentials', function (Blueprint $table) {
            $table->dropColumn(['banco', 'cuenta', 'cedula', 'telefono']);
        });
    }
};
