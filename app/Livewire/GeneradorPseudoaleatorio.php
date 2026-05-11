<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GeneradorNumero;

class GeneradorPseudoaleatorio extends Component
{
    public $metodo = 'cuadrados';
    public $semilla1, $semilla2, $constante_a;
    public $cantidad = 10;
    public $resultados = [];

    // Propiedades para la prueba de uniformidad automática
    public $chi_calculado = 0;
    public $chi_critico = 7.779; // Valor para alpha = 0.10 (90%) y GL = 4
    public $pasa_prueba = false;
    public $frecuencias = [];

    public function limpiar()
    {
        $this->reset(['semilla1', 'semilla2', 'constante_a', 'cantidad', 'resultados', 'chi_calculado', 'pasa_prueba', 'frecuencias']);
        session()->forget('error');
    }

    public function cargarHistorial($id)
    {
        $item = GeneradorNumero::find($id);
        if ($item) {
            $this->metodo = $item->metodo;
            $this->semilla1 = $item->parametros['s1'];
            $this->semilla2 = $item->parametros['s2'] ?? null;
            $this->constante_a = $item->parametros['a'] ?? null;
            $this->cantidad = count($item->lista_numeros);

            $this->resultados = $item->lista_numeros;
            $this->calcularChiCuadrado();
            $this->enviarEventoJS(); 
        }
    }

    public function generar($guardar = true)
    {
        if ($guardar) { $this->resultados = []; }

        $semillaValor = (int)$this->semilla1;
        $d = strlen((string)$semillaValor);

        if ($semillaValor < 100) {
            session()->flash('error', 'La semilla debe tener al menos 3 dígitos.');
            return;
        }

        $x_n = $semillaValor;
        $x_sec = (int)$this->semilla2;
        $a = (int)$this->constante_a;
        $tempResultados = [];

        for ($i = 0; $i < $this->cantidad; $i++) {
            if ($this->metodo == 'cuadrados') {
                $valor = $x_n * $x_n;
                $txt_op = "({$x_n})^2";
            } elseif ($this->metodo == 'productos') {
                $valor = $x_n * $x_sec;
                $txt_op = "{$x_n} * {$x_sec}";
            } else {
                $valor = $a * $x_n;
                $txt_op = "{$a} * {$x_n}";
            }

            $str_original = (string)$valor;
            
            /** * CORRECCIÓN: Para que el centro sea exacto, la cadena debe rellenarse 
             * hasta 2*d o (2*d)-1 dependiendo de la paridad de d.
             * Lo más seguro es usar 2*d para garantizar simetría.
             */
            $longitud_esperada = $d * 2;
            $str_ajustada = str_pad($str_original, $longitud_esperada, "0", STR_PAD_LEFT);
            
            // Si después del relleno la cadena es más larga de lo esperado (ej. 43^2 en d=2), 
            // recalculamos el inicio para que el recorte sea central.
            $len_actual = strlen($str_ajustada);
            $inicio = (int)(($len_actual - $d) / 2);
            $centro = substr($str_ajustada, $inicio, $d);
            
            $ri = "0." . $centro;

            $tempResultados[] = [
                'i' => $i + 1,
                'txt_op' => $txt_op,
                'resultado_op' => $str_original,
                'resultado_full' => $str_ajustada,
                'xi' => $centro,
                'ri' => number_format((float)$ri, 4, '.', ''),
                'd' => $d
            ];

            // Actualización de valores para la siguiente iteración
            if ($this->metodo == 'productos') {
                $x_n = $x_sec; 
                $x_sec = (int)$centro;
            } else {
                $x_n = (int)$centro;
            }

            // Evitar bucles infinitos si el centro degenera a 0
            if ((int)$centro == 0 && $i < $this->cantidad - 1) {
                // Opcional: podrías rellenar el resto con ceros o romper el ciclo
                break; 
            }
        }

        $this->resultados = $tempResultados;

        $this->calcularChiCuadrado();

        if ($guardar) {
            GeneradorNumero::create([
                'metodo' => $this->metodo,
                'parametros' => [
                    's1' => $this->semilla1, 
                    's2' => $this->semilla2, 
                    'a' => $this->constante_a, 
                    'd' => $d
                ],
                'lista_numeros' => $this->resultados
            ]);
        }

        $this->enviarEventoJS();
    }

    private function calcularChiCuadrado()
    {
        $n = count($this->resultados);
        if ($n == 0) return;

        $k = 5; 
        $frecuencia_esperada = $n / $k;
        $observadas = array_fill(0, $k, 0);

        foreach ($this->resultados as $res) {
            $ri = (float)$res['ri'];
            // Clasificación en 5 intervalos de 0.2 de ancho
            $idx = min((int)($ri / 0.2), 4); 
            $observadas[$idx]++;
        }

        $suma_chi = 0;
        foreach ($observadas as $oi) {
            $suma_chi += pow($oi - $frecuencia_esperada, 2) / $frecuencia_esperada;
        }

        $this->chi_calculado = number_format($suma_chi, 4);
        $this->frecuencias = $observadas;
        $this->pasa_prueba = (float)$suma_chi < $this->chi_critico;
    }

    private function enviarEventoJS()
    {
        $this->dispatch('pseudo-updated', [
            'resultados' => $this->resultados,
            'metodo' => $this->metodo,
            'params' => [
                's1' => $this->semilla1,
                'a' => $this->constante_a
            ]
        ]);
    }

    public function render()
    {
        return view('livewire.generador-pseudoaleatorio', [
            'historial' => GeneradorNumero::latest()->get()
        ])->layout('layouts.app');
    }
}