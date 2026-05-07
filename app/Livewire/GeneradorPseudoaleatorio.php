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

    public function limpiar()
    {
        $this->semilla1 = null;
        $this->semilla2 = null;
        $this->constante_a = null;
        $this->cantidad = 10;
        $this->resultados = [];
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

            $datosCargados = is_array($item->lista_numeros) ? $item->lista_numeros : [];

            // RECONSTRUCCIÓN: Si el primer elemento no tiene 'detalle', regeneramos todo el paso a paso
            if (!empty($datosCargados) && !isset($datosCargados[0]['detalle'])) {
                $this->generar(false); // true = guardar, false = solo calcular
            }
            else {
                $this->resultados = $datosCargados;
            }
        }
    }

    public function generar($guardar = true)
    {
        if ($guardar) {
            $this->resultados = [];
        }

        $semillaValor = (int)$this->semilla1;

        if ($semillaValor < 100) {
            session()->flash('error', 'La semilla debe ser de al menos 3 dígitos (mínimo 100)');
            return;
        }

        if ($this->metodo == 'productos' && (int)$this->semilla2 < 100) {
            session()->flash('error', 'La semilla secundaria debe ser de al menos 3 dígitos');
            return;
        }

        if ($this->metodo == 'constante' && (int)$this->constante_a < 100) {
            session()->flash('error', 'La constante debe ser de al menos 3 dígitos');
            return;
        }

        $d = strlen((string)$semillaValor);
        $x_n = $semillaValor;
        $x_sec = (int)$this->semilla2;
        $a = (int)$this->constante_a;

        $tempResultados = [];

        for ($i = 0; $i < $this->cantidad; $i++) {
            $detalle = "";

            if ($this->metodo == 'cuadrados') {
                $valor = $x_n * $x_n;
                $detalle = "Fórmula: {$x_n}² = $valor.";
            }
            elseif ($this->metodo == 'productos') {
                $valor = $x_n * $x_sec;
                $detalle = "Fórmula: $x_n * $x_sec = $valor.";
            }
            else {
                $valor = $a * $x_n;
                $detalle = "Fórmula: $a * $x_n = $valor.";
            }

            $str = (string)$valor;
            $longitud_esperada = $d * 2;

            if (strlen($str) < $longitud_esperada) {
                $faltantes = $longitud_esperada - strlen($str);
                $detalle .= " Se ajusta a $longitud_esperada dígitos agregando $faltantes cero(s).";
                while (strlen($str) < $longitud_esperada) {
                    $str = "0" . $str;
                }
            }

            $inicio = (int)((strlen($str) - $d) / 2);
            $centro = substr($str, $inicio, $d);
            $detalle .= " De '$str' el centro es **$centro**.";

            $ri_formateado = number_format((float)("0." . $centro), 4, '.', '');
            if ((int)$centro == 0) {
                $detalle .= " <span style='color:red; font-weight:bold;'>[DEGENERACIÓN]</span>";
            }

            $tempResultados[] = [
                'i' => $i + 1,
                'operacion' => $str,
                'xi' => $centro,
                'ri' => $ri_formateado,
                'detalle' => $detalle
            ];

            if ($this->metodo == 'productos') {
                $x_n = $x_sec;
                $x_sec = (int)$centro;
            }
            else {
                $x_n = (int)$centro;
            }
        }

        $this->resultados = $tempResultados;

        if ($guardar) {
            GeneradorNumero::create([
                'metodo' => $this->metodo,
                'parametros' => ['s1' => $this->semilla1, 's2' => $this->semilla2, 'a' => $this->constante_a],
                'lista_numeros' => $this->resultados
            ]);
        }
    }

    public function render()
    {
        return view('livewire.generador-pseudoaleatorio', [
            // Quitamos el take() para traer todos los registros creados
            'historial' => GeneradorNumero::latest()->get()
        ])->layout('layouts.app');
    }
}