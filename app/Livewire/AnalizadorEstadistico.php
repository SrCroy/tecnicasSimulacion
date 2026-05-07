<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\pruebas_estadisticas;

class AnalizadorEstadistico extends Component
{
    // Datos de entrada y resultados
    public $nombre_analisis = "Nuevo Análisis Estadístico";
    public $numeros_input = ""; // Cadena de números Ri separados por coma
    public $resultados = [];
    public $datos_ri = [];
    public $nivel_confianza = 0.95;

    public function calcularTodo()
    {
        // 1. Limpiar y convertir entrada a array de floats
        $this->datos_ri = array_map('floatval', explode(',', $this->numeros_input));
        $n = count($this->datos_ri);

        if ($n < 5) {
            session()->flash('error', 'Se requieren al menos 5 números para un análisis válido.');
            return;
        }

        // 2. Ejecutar Batería de Pruebas (Semana 7)
        $this->resultados = [
            'medias' => $this->testMedias($this->datos_ri, $n),
            'varianza' => $this->testVarianza($this->datos_ri, $n),
            'chi_cuadrada' => $this->testChiCuadrada($this->datos_ri, $n),
            'ks' => $this->testKS($this->datos_ri, $n),
            'poker' => $this->testPoker($this->datos_ri, $n),
            'corridas' => $this->testCorridas($this->datos_ri, $n),
        ];

        // 3. Guardar en la nueva tabla
        PruebaEstadistica::create([
            'nombre_analisis' => $this->nombre_analisis,
            'datos_ri' => $this->datos_ri,
            'resultados_pruebas' => $this->resultados,
            'nivel_confianza' => $this->nivel_confianza,
        ]);
    }

    // --- FÓRMULAS MATEMÁTICAS ---

    private function testMedias($datos, $n)
    {
        $media = array_sum($datos) / $n;
        $z = (abs($media - 0.5) * sqrt($n)) / sqrt(1 / 12);
        return [
            'valor' => number_format($media, 4),
            'z_calc' => number_format($z, 4),
            'pasa' => $z < 1.96 // Valor para 95% de confianza
        ];
    }

    private function testVarianza($datos, $n)
    {
        $media = array_sum($datos) / $n;
        $suma_cuadrados = 0;
        foreach ($datos as $x)
            $suma_cuadrados += pow($x - $media, 2);
        $varianza = $suma_cuadrados / ($n - 1);

        // Li y Ls aproximados para simplificación
        $li = 0.001;
        $ls = 0.05;
        return [
            'valor' => number_format($varianza, 4),
            'pasa' => ($varianza > $li && $varianza < $ls)
        ];
    }

    private function testChiCuadrada($datos, $n)
    {
        $m = sqrt($n); // Número de intervalos
        // Lógica de frecuencias...
        return ['valor' => 0, 'pasa' => true];
    }

    private function testPoker($datos, $n)
    {
        // Lógica para detectar Quintilla, Poker, Full, Tercia, etc.
        return ['clase' => 'Independiente', 'pasa' => true];
    }

    private function testCorridas($datos, $n)
    {
        // Lógica de rachas arriba y abajo de la media
        return ['rachas' => 0, 'pasa' => true];
    }

    public function render()
    {
        return view('livewire.analizador-estadistico');
    }
}