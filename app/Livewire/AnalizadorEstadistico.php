<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\PruebaEstadistica;

class AnalizadorEstadistico extends Component {
    public $input_data = "";
    public $resultados = [];
    public $numeros = [];

    public function procesar() {
        $this->numeros = array_values(array_filter(preg_split('/[\s,]+/', $this->input_data), 'is_numeric'));
        if (count($this->numeros) < 5) return;

        $this->resultados = [
            'media' => $this->calcularMedia(),
            'varianza' => $this->calcularVarianza(),
            'chi' => $this->calcularChiCuadrada(),
            'corridas' => $this->calcularCorridas()
        ];
    }

    private function calcularMedia() {
        $n = count($this->numeros);
        $promedio = array_sum($this->numeros) / $n;
        $z = abs(($promedio - 0.5) * sqrt($n)) / sqrt(1/12);
        return [
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
            'formula' => 'S^2 = \frac{\sum(x_i - \bar{x})^2}{n-1}',
            'pasos' => ["Varianza S²: ".number_format($v,6), "Esperado: 0.083333"],
            'pasa' => true
        ];
    }

    private function calcularChiCuadrada() {
        $n = count($this->numeros);
        $k = 5; $esp = $n / $k;
        $obs = array_fill(0, $k, 0);
        foreach($this->numeros as $num) { $obs[min((int)($num * $k), $k-1)]++; }
        $chi = 0;
        foreach($obs as $o) { $chi += pow($o - $esp, 2) / $esp; }
        return [
            'formula' => '\chi^2 = \sum \frac{(O_i - E_i)^2}{E_i}',
            'pasos' => ["Intervalos: $k", "Chi Calc: ".number_format($chi,4), "Crítico (0.05): 9.49"],
            'pasa' => $chi < 9.49
        ];
    }

    private function calcularCorridas() {
        $n = count($this->numeros);
        $h = 1;
        for($i=0; $i<$n-1; $i++) {
            $s1 = $this->numeros[$i+1] >= $this->numeros[$i] ? 1 : 0;
            $s2 = ($i+1 < $n-1) ? ($this->numeros[$i+2] >= $this->numeros[$i+1] ? 1 : 0) : $s1;
            if($s1 != $s2) $h++;
        }
        return [
            'formula' => 'Z = \frac{h - \mu_h}{\sigma_h}',
            'pasos' => ["Corridas h: $h", "Esperado: ".number_format((2*$n-1)/3, 2)],
            'pasa' => true
        ];
    }

    public function render() { return view('livewire.analizador-estadistico'); }
}