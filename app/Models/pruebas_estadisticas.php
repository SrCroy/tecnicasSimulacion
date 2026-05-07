<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pruebas_estadisticas extends Model
{
    use HasFactory;

    protected $table = 'pruebas_estadisticas';

    protected $fillable = [
        'nombre_analisis',
        'datos_ri',
        'resultados_pruebas',
        'nivel_confianza'
    ];

    // Clave para manejar los arrays de los tests matemáticos
    protected $casts = [
        'datos_ri' => 'array',
        'resultados_pruebas' => 'array',
    ];
}