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
    
    // Propiedades estadísticas base
    public $media = 0, $varianza = 0, $periodo = 0;
    public $poker = ['TD' => 0, '1P' => 0, '2P_T' => 0, 'PK' => 0];
    
    // Resultados de Pruebas (Literales A, B, C, D)
    public $media_z = 0, $media_pasa = false;
    public $varianza_pasa = false;
    public $chi_calc = 0, $chi_pasa = false;
    public $corridas_h = 0, $corridas_z = 0, $corridas_pasa = false;
    public $corridas_media_b = 0, $corridas_media_z = 0, $corridas_media_pasa = false;

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
                case 'mixto': case 'lineal': $proximo_xn = ($a * $xn + $c) % $m; break;
                case 'segundo_orden': $proximo_xn = ($a * $xn + $b * $xn_anterior) % $m; break;
                case 'multiplicativo': $proximo_xn = ($a * $xn) % $m; break;
                case 'aditivo': $proximo_xn = ($xn + $c) % $m; break;
                case 'cuadratico': $proximo_xn = ($a * pow($xn, 2) + $b * $xn + $c) % $m; break;
            }

            if ($this->periodo == 0 && in_array($proximo_xn, $historial_xn)) {
                $this->periodo = count($historial_xn);
            }
            $historial_xn[] = $proximo_xn;

            $ri = $proximo_xn / $m;

            $this->resultados[] = [
                'i' => $i + 1,
                'xn' => $xn,
                'proximo_xn' => $proximo_xn,
                'ri' => number_format($ri, 6, '.', '')
            ];

            if ($this->metodo == 'segundo_orden') { $xn_anterior = $xn; }
            $xn = $proximo_xn;
        }

        $this->calcularEstadisticas();
    }

    private function calcularEstadisticas()
    {
        if (empty($this->resultados)) return;

        $n = count($this->resultados);
        $ri = array_column($this->resultados, 'ri');

        // 1. Media y Varianza
        $this->media = array_sum($ri) / $n;
        $this->media_z = abs(($this->media - 0.5) * sqrt($n)) / sqrt(1/12);
        $this->media_pasa = $this->media_z < 1.96;

        $sumaSqr = 0;
        foreach ($ri as $v) { $sumaSqr += pow($v - $this->media, 2); }
        $this->varianza = $n > 1 ? $sumaSqr / ($n - 1) : 0;
        $this->varianza_pasa = ($this->varianza > 0.07 && $this->varianza < 0.09);

        // 2. Uniformidad (Chi-Cuadrada)
        $k = 5; $esp = $n / $k; $obs = array_fill(0, $k, 0);
        foreach ($ri as $v) { $idx = min((int)($v * $k), $k - 1); $obs[$idx]++; }
        $this->chi_calc = 0;
        foreach ($obs as $o) { $this->chi_calc += pow($o - $esp, 2) / $esp; }
        $this->chi_pasa = $this->chi_calc < 9.49;

        // 3. Corridas Arriba/Abajo
        $s = []; 
        for ($i = 0; $i < $n - 1; $i++) { $s[] = ($ri[$i+1] > $ri[$i]) ? '+' : '-'; }
        $h = 1; 
        for ($i = 0; $i < count($s) - 1; $i++) { if ($s[$i] != $s[$i+1]) $h++; }
        $mu_h = (2 * $n - 1) / 3;
        $sigma_h = sqrt((16 * $n - 29) / 90);
        $this->corridas_h = $h;
        $this->corridas_z = abs(($h - $mu_h) / $sigma_h);
        $this->corridas_pasa = $this->corridas_z < 1.96;

        // 4. Corridas sobre la Media
        $n1 = 0; $n2 = 0; $sec = [];
        foreach ($ri as $v) { 
            if ($v >= 0.5) { $n1++; $sec[] = '1'; } else { $n2++; $sec[] = '0'; }
        }
        $b = 1;
        for ($i = 0; $i < count($sec) - 1; $i++) { if ($sec[$i] != $sec[$i+1]) $b++; }
        
        // CORRECCIÓN AQUÍ: Se eliminó el $ antes del 2
        $mu_b = ((2 * $n1 * $n2) / $n) + 0.5;
        $den_b = ($n * $n * ($n - 1));
        $sigma_b = ($den_b > 0) ? sqrt((2 * $n1 * $n2 * (2 * $n1 * $n2 - $n)) / $den_b) : 1;
        
        $this->corridas_media_b = $b;
        $this->corridas_media_z = ($sigma_b > 0) ? abs(($b - $mu_b) / $sigma_b) : 0;
        $this->corridas_media_pasa = $this->corridas_media_z < 1.96;

        // 5. Poker
        $this->poker = ['TD' => 0, '1P' => 0, '2P_T' => 0, 'PK' => 0];
        foreach ($ri as $v) {
            $dec = str_pad(substr(explode('.', $v)[1] ?? '0', 0, 4), 4, '0');
            $counts = array_count_values(str_split($dec));
            $dist = count($counts);
            if ($dist == 4) $this->poker['TD']++;
            elseif ($dist == 3) $this->poker['1P']++;
            elseif ($dist == 2) $this->poker['2P_T']++;
            elseif ($dist == 1) $this->poker['PK']++;
        }
    }

    private function enviarEventoJS()
    {
        $this->dispatch('generador-updated', [
            'resultados' => $this->resultados,
            'metodo' => $this->metodo,
            'stats' => [
                'media' => number_format($this->media, 4),
                'media_z' => number_format($this->media_z, 4),
                'media_pasa' => $this->media_pasa,
                'varianza' => number_format($this->varianza, 4),
                'chi_calc' => number_format($this->chi_calc, 4),
                'chi_pasa' => $this->chi_pasa,
                'corridas' => [
                    'h' => $this->corridas_h,
                    'z' => number_format($this->corridas_z, 4),
                    'pasa' => $this->corridas_pasa
                ],
                'corridas_media' => [
                    'b' => $this->corridas_media_b,
                    'z' => number_format($this->corridas_media_z, 4),
                    'pasa' => $this->corridas_media_pasa
                ],
                'poker' => $this->poker,
                'periodo' => $this->periodo
            ],
            'params' => ['a' => $this->a, 'm' => $this->m]
        ]);
    }

    public function limpiar()
    {
        $this->reset(['x0', 'a', 'c', 'm', 'resultados', 'media', 'varianza', 'periodo', 'poker', 'media_z', 'media_pasa', 'chi_calc', 'chi_pasa', 'corridas_h', 'corridas_z', 'corridas_pasa', 'corridas_media_b', 'corridas_media_z', 'corridas_media_pasa']);
    }

    public function render()
    {
        return view('livewire.generador-congruencial', [
            'historial' => MetodoCongruencial::latest()->take(10)->get()
        ])->layout('layouts.app');
    }
}