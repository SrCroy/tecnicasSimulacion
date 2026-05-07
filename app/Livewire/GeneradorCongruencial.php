<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MetodoCongruencial;

class GeneradorCongruencial extends Component
{
    public $metodo = 'lineal';
    public $x0, $a, $b, $c, $m;
    public $cantidad = 7;
    public $resultados = [];

    public function limpiar()
    {
        $this->x0 = null;
        $this->a = null;
        $this->b = null;
        $this->c = null;
        $this->m = null;
        $this->cantidad = 7;
        $this->resultados = [];
        session()->forget('error');
    }

    public function generar()
    {
        $this->resultados = [];
        if (!(int)$this->m > 1) {
            session()->flash('error', 'El módulo (m) debe ser mayor a 1.');
            return;
        }

        $m = (int)$this->m;
        $cant = (int)$this->cantidad;

        // Secuencia predefinida para el Algoritmo Aditivo (Ejemplo 2.9 del libro)
        $secuencia_aditiva = [65, 89, 98, 3, 69];

        $xn = (int)$this->x0;
        $a = (int)$this->a;
        $b = (int)$this->b;
        $c = (int)$this->c;

        for ($i = 0; $i < $cant; $i++) {
            $detalle = "";
            $proximo_xn = 0;

            switch ($this->metodo) {
                case 'lineal':
                    $val_op = ($a * $xn + $c);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a * $xn + $c) mod $m. Operación: $val_op mod $m";
                    break;

                case 'multiplicativo':
                    $val_op = ($a * $xn);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a * $xn) mod $m. Operación: $val_op mod $m";
                    break;

                case 'aditivo':
                    // X(n+1) = (Xn + Xn-k) mod m
                    $val_actual = $secuencia_aditiva[count($secuencia_aditiva) - 1];
                    $val_rezago = $secuencia_aditiva[$i];
                    $val_op = ($val_actual + $val_rezago);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($val_actual + $val_rezago) mod $m. Operación: $val_op mod $m";
                    $secuencia_aditiva[] = $proximo_xn;
                    $xn = $val_actual;
                    break;

                case 'cuadratico':
                    // Fórmula completa: (a*xn² + b*xn + c) mod m
                    $val_op = ($a * pow($xn, 2) + $b * $xn + $c);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a*xn² + $b*xn + $c) mod $m. Operación: $val_op mod $m";
                    break;

                case 'blum_blum':
                    $val_op = pow($xn, 2);
                    $proximo_xn = (int)$val_op % $m;
                    $detalle = "Fórmula: xn² mod $m. Operación: $val_op mod $m";
                    break;

                case 'no_lineal':
                    $val_op = ($a * ($xn + 1) + $c);
                    $proximo_xn = $val_op % $m;
                    $detalle = "Fórmula: ($a*(xn+1)+$c) mod $m";
                    break;
            }

            // Lógica de TRUNCADO a 4 decimales y divisor m-1
            $valor_real_ri = $proximo_xn / ($m - 1);
            $ri_truncado = floor($valor_real_ri * 10000) / 10000;
            $ri = number_format($ri_truncado, 4, '.', '');

            $this->resultados[] = [
                'i' => $i + 1,
                'xn' => $xn,
                'proximo_xn' => $proximo_xn,
                'ri' => $ri,
                'detalle' => $detalle . ". Resultado: **$proximo_xn**"
            ];

            // Actualizar xn para la siguiente iteración (excepto en aditivo que usa su propia secuencia)
            if ($this->metodo != 'aditivo') {
                $xn = $proximo_xn;
            }
        }

        // Guardar en la base de datos
        MetodoCongruencial::create([
            'metodo' => $this->metodo,
            'parametros' => [
                'x0' => $this->x0,
                'a' => $a,
                'b' => $b,
                'c' => $c,
                'm' => $m
            ],
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