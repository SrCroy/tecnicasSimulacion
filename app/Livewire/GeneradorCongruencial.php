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
    
    // Propiedades para las pruebas estadísticas
    public $media = 0, $varianza = 0, $periodo = 0;
    public $poker = ['TD' => 0, '1P' => 0, '2P_T' => 0, 'PK' => 0];

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
            
            $this->calcularEstadisticas();
            $this->enviarEventoJS();
        }
    }

    private function ejecutarCalculos()
    {
        $this->resultados = [];
        $this->periodo = 0;
        
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

        $historial_xn = [];

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

            if ($this->periodo == 0 && in_array($proximo_xn, $historial_xn)) {
                $this->periodo = count($historial_xn);
            }
            $historial_xn[] = $proximo_xn;

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

        $this->calcularEstadisticas();
    }

    private function calcularEstadisticas()
    {
        if (empty($this->resultados)) return;

        $n = count($this->resultados);
        $coleccionRi = array_column($this->resultados, 'ri');

        // 1. Media
        $this->media = array_sum($coleccionRi) / $n;

        // 2. Varianza
        $sumaCuadrados = 0;
        foreach ($coleccionRi as $val) {
            $sumaCuadrados += pow($val - $this->media, 2);
        }
        $this->varianza = $n > 1 ? $sumaCuadrados / ($n - 1) : 0;

        // 3. Prueba de Póker (Clasificación de 4 decimales)
        $this->poker = ['TD' => 0, '1P' => 0, '2P_T' => 0, 'PK' => 0];
        foreach ($coleccionRi as $ri) {
            $partes = explode('.', $ri);
            $decimales = isset($partes[1]) ? str_pad(substr($partes[1], 0, 4), 4, '0') : '0000';
            
            $conteo = array_count_values(str_split($decimales));
            $distintos = count($conteo);

            if ($distintos == 4) {
                $this->poker['TD']++; // Todos Diferentes
            } elseif ($distintos == 3) {
                $this->poker['1P']++; // Un Par
            } elseif ($distintos == 2) {
                $this->poker['2P_T']++; // Dos Pares o Tercia
            } elseif ($distintos == 1) {
                $this->poker['PK']++; // Póker
            }
        }
    }

    private function enviarEventoJS()
    {
        $this->dispatch('generador-updated', [
            'resultados' => $this->resultados,
            'metodo' => $this->metodo,
            'stats' => [
                'media' => number_format($this->media, 4),
                'varianza' => number_format($this->varianza, 4),
                'periodo' => $this->periodo,
                'poker' => $this->poker
            ],
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
        $this->reset(['x0', 'x_atras', 'a', 'b', 'c', 'm', 'resultados', 'media', 'varianza', 'periodo', 'poker']);
    }

    public function render()
    {
        return view('livewire.generador-congruencial', [
            'historial' => MetodoCongruencial::latest()->take(10)->get()
        ])->layout('layouts.app');
    }
}