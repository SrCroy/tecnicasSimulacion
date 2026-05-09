<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MetodoCongruencial;

class GeneradorCongruencial extends Component
{
    public $metodo = 'lineal';
    public $x0, $x_atras, $a, $b, $c, $m;
    public $cantidad = 7;
    public $resultados = [];

    public function generar()
    {
        $this->resultados = [];
        if (!(int)$this->m > 1) {
            session()->flash('error', 'El módulo (m) debe ser mayor a 1.');
            return;
        }

        $m = (int)$this->m;
        $cant = (int)$this->cantidad;
        $xn = (int)$this->x0;
        $xn_anterior = (int)$this->x_atras; 
        $a = (int)$this->a;
        $b = (int)$this->b;
        $c = (int)$this->c;

        for ($i = 0; $i < $cant; $i++) {
            $detalle = "";
            $proximo_xn = 0;

            switch ($this->metodo) {
                case 'mixto': // Para incisos a y b de la guía
                    $val_op = ($a * $xn + $c);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula Mixta: ($a * $xn + $c) mod $m. Operación: $val_op mod $m";
                    break;

                case 'segundo_orden': // Inciso E
                    $val_op = ($a * $xn + $b * $xn_anterior);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a*$xn + $b*$xn_anterior) mod $m";
                    break;
                
                case 'lineal':
                    $val_op = ($a * $xn + $c);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a * $xn + $c) mod $m";
                    break;

                case 'multiplicativo': // Inciso C
                    $val_op = ($a * $xn);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a * $xn) mod $m";
                    break;

                case 'aditivo': // Inciso D (cuando a=1)
                    $proximo_xn = ($xn + $c) % $m;
                    $detalle = "Fórmula Aditiva: ($xn + $c) mod $m";
                    break;

                case 'cuadratico':
                    $val_op = ($a * pow($xn, 2) + $b * $xn + $c);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula Cuadrática: ($a*xn² + $b*xn + $c) mod $m";
                    break;
            }

            // Cálculo de Ri con truncado a 4 decimales
            $ri = number_format(floor(($proximo_xn / ($m - 1)) * 10000) / 10000, 4, '.', '');

            $this->resultados[] = [
                'i' => $i + 1,
                'xn' => $xn,
                'proximo_xn' => $proximo_xn,
                'ri' => $ri,
                'detalle' => $detalle . ". Resultado: **$proximo_xn**"
            ];

            if ($this->metodo == 'segundo_orden') {
                $xn_anterior = $xn;
            }
            $xn = $proximo_xn;
        }

        MetodoCongruencial::create([
            'metodo' => $this->metodo,
            'parametros' => ['x0' => $this->x0, 'x_atras' => $this->x_atras, 'a' => $a, 'b' => $b, 'c' => $c, 'm' => $m],
            'lista_numeros' => $this->resultados
        ]);
    }

    public function render()
    {
        return view('livewire.generador-congruencial', [
            'historial' => MetodoCongruencial::latest()->get()
        ])->layout('layouts.app');
    }
}