<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MetodoCongruencial;

class GeneradorCongruencial extends Component
{
    public $metodo = 'lineal';
    public $x0, $x_atras, $a, $b, $c, $m;
    public $cantidad = 10;
    public $resultados = [];

    public function generar()
    {
        $this->ejecutarCalculos();

        MetodoCongruencial::create([
            'metodo' => $this->metodo,
            'parametros' => [
                'x0' => $this->x0, 
                'x_atras' => $this->x_atras, 
                'a' => (int)$this->a, 
                'b' => (int)$this->b, 
                'c' => (int)$this->c, 
                'm' => (int)$this->m
            ],
            'lista_numeros' => $this->resultados
        ]);

        $this->enviarEventoJS();
    }

    public function cargarHistorial($id)
    {
        $registro = MetodoCongruencial::find($id);
        if ($registro) {
            $this->metodo = $registro->metodo;
            $this->x0 = $registro->parametros['x0'];
            $this->x_atras = $registro->parametros['x_atras'] ?? null;
            $this->a = $registro->parametros['a'] ?? null;
            $this->b = $registro->parametros['b'] ?? null;
            $this->c = $registro->parametros['c'] ?? null;
            $this->m = $registro->parametros['m'];
            $this->resultados = $registro->lista_numeros;

            $this->enviarEventoJS();
        }
    }

    private function ejecutarCalculos()
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
            $proximo_xn = 0;

            switch ($this->metodo) {
                case 'mixto':
                case 'lineal':
                    $proximo_xn = ($a * $xn + $c) % $m;
                    break;

                case 'segundo_orden':
                    $proximo_xn = ($a * $xn + $b * $xn_anterior) % $m;
                    break;

                case 'multiplicativo':
                    $proximo_xn = ($a * $xn) % $m;
                    break;

                case 'aditivo':
                    $proximo_xn = ($xn + $c) % $m;
                    break;

                case 'cuadratico':
                    $proximo_xn = ($a * pow($xn, 2) + $b * $xn + $c) % $m;
                    break;
            }

            $ri = ($m > 1) ? floor(($proximo_xn / ($m - 1)) * 10000) / 10000 : 0;

            $this->resultados[] = [
                'i' => $i + 1,
                'xn' => $xn,
                'xn_anterior' => ($this->metodo == 'segundo_orden') ? $xn_anterior : null,
                'proximo_xn' => $proximo_xn,
                'ri' => number_format($ri, 4, '.', '')
            ];

            if ($this->metodo == 'segundo_orden') {
                $xn_anterior = $xn;
            }
            $xn = $proximo_xn;
        }
    }

    private function enviarEventoJS()
    {
        $this->dispatch('generador-updated', [
            'resultados' => $this->resultados,
            'metodo' => $this->metodo,
            'params' => [
                'a' => $this->a, 
                'b' => $this->b, 
                'c' => $this->c, 
                'm' => $this->m
            ]
        ]);
    }

    public function limpiar()
    {
        $this->reset(['x0', 'x_atras', 'a', 'b', 'c', 'm', 'resultados']);
    }

    public function render()
    {
        return view('livewire.generador-congruencial', [
            'historial' => MetodoCongruencial::latest()->take(10)->get()
        ])->layout('layouts.app');
    }
}