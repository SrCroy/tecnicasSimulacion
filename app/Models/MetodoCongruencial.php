<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoCongruencial extends Model
{
    use HasFactory;

    protected $fillable = [
        'metodo',
        'parametros',
        'lista_numeros'
    ];

    /**
     * Casts para manejar JSON como arrays de PHP automáticamente
     */
    protected $casts = [
        'parametros' => 'array',
        'lista_numeros' => 'array',
    ];
}