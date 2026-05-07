<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = ['model_type', 'inputs', 'results'];

    protected $casts = [
        'inputs' => 'array',
        'results' => 'array',
    ];
}
