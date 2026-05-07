<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pruebas_estadisticas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_analisis'); // Ej: "Analisis Lote #1"
            $table->json('datos_ri'); // Los números Ri analizados
            $table->json('resultados_pruebas'); // Resultados de Medias, Poker, etc.
            $table->float('nivel_confianza')->default(0.95);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pruebas_estadisticas');
    }
};