<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneradorNumero extends Model
{
    protected $fillable = ['metodo', 'parametros', 'lista_numeros'];

    protected $casts = [
        'parametros' => 'array',
        'lista_numeros' => 'array',
    ];
}