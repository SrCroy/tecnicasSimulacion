<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pruebas_estadisticas extends Model
{
    protected $fillable = ['nombre_set', 'datos_crudos', 'resultados_json'];
    protected $casts = ['resultados_json' => 'array'];
}