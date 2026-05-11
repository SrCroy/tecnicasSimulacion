<?php

namespace App\Livewire;

use App\Models\pruebas_estadisticas;
use Livewire\Component;

class AnalizadorEstadistico extends Component {
    public $input_data = "";
    public $resultados = [];
    public $numeros = [];

    public function limpiar() {
        $this->reset(['input_data', 'resultados', 'numeros']);
    }

    public function cargarHistorial($id) {
        $item = pruebas_estadisticas::find($id);
        if ($item) {
            $this->input_data = $item->datos_crudos;
            $this->resultados = $item->resultados_json;
            
            $this->numeros = array_values(array_filter(preg_split('/[\s,]+/', $this->input_data), function($val) {
                return is_numeric($val) && $val !== '';
            }));
            
            $this->dispatch('formulas-ready');
        }
    }

    public function procesar() {
        $this->numeros = array_values(array_filter(preg_split('/[\s,]+/', $this->input_data), function($val) {
            return is_numeric($val) && $val !== '';
        }));

        if (count($this->numeros) < 5) {
            session()->flash('error', 'Se necesitan al menos 5 números.');
            return;
        }

        // Ordenamos los resultados para la vista
        $this->resultados = [
            'media' => $this->calcularMedia(),
            'varianza' => $this->calcularVarianza(),
            'chi' => $this->calcularChiCuadrada(),
            'corridas' => $this->calcularCorridas(),
            'poker' => $this->calcularPoker()
        ];

        pruebas_estadisticas::create([
            'nombre_set' => 'Análisis de ' . count($this->numeros) . ' datos',
            'datos_crudos' => $this->input_data, 
            'resultados_json' => $this->resultados 
        ]);

        $this->dispatch('formulas-ready');
    }

    private function calcularMedia() {
        $n = count($this->numeros);
        $promedio = array_sum($this->numeros) / $n;
        $z = abs(($promedio - 0.5) * sqrt($n)) / sqrt(1/12);
        return [
            'titulo' => 'Prueba de Medias',
            'formula' => 'Z = \frac{|\bar{x} - 0.5|\sqrt{n}}{\sqrt{1/12}}',
            'pasos' => ["n: $n", "Media: ".number_format($promedio,4), "Z: ".number_format($z,4)],
            'pasa' => $z < 1.96
        ];
    }

    private function calcularVarianza() {
        $n = count($this->numeros);
        $prom = array_sum($this->numeros) / $n;
        $v = array_reduce($this->numeros, fn($a, $b) => $a + pow($b - $prom, 2)) / ($n - 1);
        return [
            'titulo' => 'Prueba de Varianza',
            'formula' => 'S^2 = \frac{\sum(x_i - \bar{x})^2}{n-1}',
            'pasos' => ["Varianza S²: ".number_format($v,6), "Esperado: 0.083333"],
            'pasa' => $v > 0.04 && $v < 0.13 
        ];
    }

    private function calcularChiCuadrada() {
        $n = count($this->numeros);
        $k = 5; $esp = $n / $k;
        $obs = array_fill(0, $k, 0);
        foreach($this->numeros as $num) { 
            $idx = min((int)($num * $k), $k-1);
            $obs[$idx]++; 
        }
        $chi = 0;
        foreach($obs as $o) { $chi += pow($o - $esp, 2) / $esp; }
        return [
            'titulo' => 'Prueba de Chi-Cuadrada',
            'formula' => '\chi^2 = \sum \frac{(O_i - E_i)^2}{E_i}',
            'pasos' => ["Intervalos: $k", "Chi Calc: ".number_format($chi,4), "Crítico (0.05): 9.49"],
            'pasa' => $chi < 9.49
        ];
    }

    private function calcularCorridas() {
        $n = count($this->numeros);
        $h = 1;
        $s = [];
        
        // Generar secuencia de signos
        for($i=0; $i<$n-1; $i++) {
            $s[] = ($this->numeros[$i+1] >= $this->numeros[$i]) ? 1 : 0;
        }
        // Contar corridas
        for($i=0; $i<count($s)-1; $i++) {
            if($s[$i] != $s[$i+1]) $h++;
        }

        // CÁLCULO ESTADÍSTICO COMPLETO
        $mu = (2 * $n - 1) / 3;
        $sigma = sqrt((16 * $n - 29) / 90);
        $z = abs(($h - $mu) / $sigma);

        return [
            'titulo' => 'Prueba de Corridas',
            'formula' => 'Z = \frac{|h - \mu_h|}{\sigma_h}',
            'pasos' => [
                "Corridas h: $h", 
                "Esperado μ: ".number_format($mu, 2),
                "Desv. Est σ: ".number_format($sigma, 4),
                "Estadístico Z: ".number_format($z, 4)
            ],
            'pasa' => $z < 1.96
        ];
    }

    private function calcularPoker() {
        $n = count($this->numeros);
        $conteo = ['TD' => 0, '1P' => 0, '2P_T' => 0, 'PK' => 0];
        
        // Probabilidades teóricas para 4 decimales
        $prob = ['TD' => 0.5040, '1P' => 0.4320, '2P_T' => 0.0630, 'PK' => 0.0010];

        foreach ($this->numeros as $num) {
            $partes = explode('.', (string)$num);
            $decimales = isset($partes[1]) ? str_pad(substr($partes[1], 0, 4), 4, '0') : '0000';
            
            $digitos = str_split($decimales);
            $frecuencia = array_count_values($digitos);
            $distintos = count($frecuencia);

            if ($distintos == 4) $conteo['TD']++;
            elseif ($distintos == 3) $conteo['1P']++;
            elseif ($distintos == 2) $conteo['2P_T']++;
            elseif ($distintos == 1) $conteo['PK']++;
        }

        // CÁLCULO CHI-CUADRADO PÓKER
        $chi_poker = 0;
        foreach ($conteo as $cat => $obs) {
            $esp = $n * $prob[$cat];
            if ($esp > 0) {
                $chi_poker += pow($obs - $esp, 2) / $esp;
            }
        }

        return [
            'titulo' => 'Prueba de Póker',
            'formula' => '\chi^2 = \sum \frac{(O_i - E_i)^2}{E_i}',
            'pasos' => [
                "TD: {$conteo['TD']} | 1P: {$conteo['1P']} | Otros: " . ($conteo['2P_T'] + $conteo['PK']),
                "Chi Calc: ".number_format($chi_poker, 4),
                "Crítico (0.05): 7.81"
            ],
            'pasa' => $chi_poker < 7.81
        ];
    }

    public function render() { 
        return view('livewire.analizador-estadistico', [
            'historial' => pruebas_estadisticas::latest()->take(10)->get()
        ])->layout('layouts.app'); 
    }
}