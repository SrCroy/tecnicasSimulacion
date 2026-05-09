<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Calculation;

class QueueCalculator extends Component
{
    public $model = 'MM1';
    public $lambda = 0, $mu = 0, $s = 1, $sigma2 = 0, $k = 5;
    public $results = null;
    public $steps = [];

    private function factorial($n) {
        return ($n <= 1) ? 1 : $n * $this->factorial($n - 1);
    }

    public function clear() {
        $this->reset(['lambda', 'mu', 's', 'sigma2', 'k', 'results', 'steps']);
    }

    public function loadCalculation($id) {
        $calc = Calculation::find($id);
        if ($calc) {
            $this->model = $calc->model_type;
            $this->lambda = $calc->inputs['lambda'];
            $this->mu = $calc->inputs['mu'];
            $this->s = $calc->inputs['s'] ?? 1;
            $this->k = $calc->inputs['k'] ?? 5;
            $this->sigma2 = $calc->inputs['v'] ?? 0;
            $this->runLogic(false); 
        }
    }

    public function calculate() { 
        $this->runLogic(true); 
    }

    private function runLogic($shouldSave = false) {
        if (!$this->lambda || !$this->mu || $this->mu == 0) {
            session()->flash('error', 'Llene Lambda y Mu (Mu no puede ser 0)');
            return;
        }

        $l = floatval($this->lambda);
        $m = floatval($this->mu);
        $v = floatval($this->sigma2 ?: 0);
        $s = intval($this->s ?: 1);
        $k = intval($this->k ?: 5);
        
        $this->steps = [];
        $res = [];

        try {
            if ($this->model == 'MM1') {
                $rho = $l / $m;
                if ($rho >= 1) throw new \Exception("Inestable (λ ≥ μ)");
                $lq = pow($l, 2) / ($m * ($m - $l));
                $wq = $lq / $l; $ws = $wq + (1 / $m); $ls = $l * $ws;
                
                // En M/M/1, P0 es 1 - rho
                $p0 = 1 - $rho;

                $res = [
                    'rho' => $rho, 
                    'Lq' => $lq, 
                    'Wq' => $wq, 
                    'W' => $ws, 
                    'L' => $ls, 
                    'Pw' => $rho, 
                    'P0' => $p0
                ];
            } 
            elseif ($this->model == 'MMs') {
                $r = $l / $m; 
                $rho = $r / $s;
                if ($rho >= 1) throw new \Exception("Inestable (ρ >= 1)");
                
                $sum = 0;
                for ($n = 0; $n < $s; $n++) { 
                    $sum += pow($r, $n) / $this->factorial($n); 
                }
                
                $part2 = pow($r, $s) / ($this->factorial($s) * (1 - $rho));
                $p0 = 1 / ($sum + $part2);
                
                $pw = (pow($r, $s) * $p0) / ($this->factorial($s) * (1 - $rho));
                $lq = ($pw * $rho) / (1 - $rho);
                $wq = $lq / $l; 
                $ws = $wq + (1 / $m); 
                $ls = $l * $ws;

                $res = [
                    'rho' => $rho, 
                    'Lq' => $lq, 
                    'Wq' => $wq, 
                    'W' => $ws, 
                    'L' => $ls, 
                    'Pw' => $pw, 
                    'P0' => $p0
                ];
            }
            elseif ($this->model == 'MG1') {
                $rho = $l / $m;
                if ($rho >= 1) throw new \Exception("Inestable (λ ≥ μ)");
                $lq = (pow($l, 2) * $v + pow($rho, 2)) / (2 * (1 - $rho));
                $wq = $lq / $l; $ws = $wq + (1/$m); $ls = $l * $ws;
                
                $p0 = 1 - $rho;

                $res = [
                    'rho' => $rho, 
                    'Lq' => $lq, 
                    'Wq' => $wq, 
                    'W' => $ws, 
                    'L' => $ls, 
                    'Pw' => $rho, 
                    'P0' => $p0
                ];
            }
            elseif ($this->model == 'MM1K') {
                $r = $l / $m;
                $p0 = ($r == 1) ? (1 / ($k + 1)) : ((1 - $r) / (1 - pow($r, $k + 1)));
                $pk = pow($r, $k) * $p0; 
                $leff = $l * (1 - $pk);
                $ls = ($r == 1) ? ($k / 2) : ($r * (1 - ($k + 1) * pow($r, $k) + $k * pow($r, $k + 1))) / ((1 - $r) * (1 - pow($r, $k + 1)));
                $ws = $ls / $leff; 
                $wq = $ws - (1/$m); 
                $lq = $leff * $wq;

                $res = [
                    'rho' => $leff/$m, 
                    'Lq' => $lq, 
                    'Wq' => $wq, 
                    'W' => $ws, 
                    'L' => $ls, 
                    'Pw' => $pk, 
                    'P0' => $p0
                ];
            }

            $this->results = $res;

            // ENVIAR DATOS COMPLETOS AL JAVASCRIPT
            $this->dispatch('calculate-updated', [
                'results' => $this->results,
                'model'   => $this->model,
                'params'  => [
                    'l' => $l,
                    'm' => $m,
                    's' => $s,
                    'v' => $v,
                    'k' => $k
                ]
            ]);

            if ($shouldSave) {
                Calculation::create([
                    'model_type' => $this->model,
                    'inputs' => ['lambda'=>$l,'mu'=>$m,'s'=>$s,'v'=>$v,'k'=>$k],
                    'results' => $res
                ]);
            }
        } catch (\Exception $e) { 
            session()->flash('error', $e->getMessage()); 
        }
    }

    public function render() { 
        return view('livewire.queue-calculator', [
            'history' => Calculation::latest()->take(10)->get()
        ]); 
    }
}