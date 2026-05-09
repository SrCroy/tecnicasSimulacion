<div class="max-w-7xl mx-auto px-6 py-10">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('dashboard') }}" class="text-xs font-mono text-neutral-400 hover:text-neutral-600 transition-colors flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>volver</span>
            </a>
            <span class="text-neutral-300">|</span>
            <span class="text-xs font-mono text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full border border-cyan-200">módulo / 02</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-neutral-900">
            Teoría de <span class="text-cyan-600">Colas</span>
        </h1>
        <p class="text-sm font-mono text-neutral-500 mt-2">
            Modelos de líneas de espera — M/M/1 · M/M/s · M/G/1 · M/M/1/K
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-1 bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm sticky top-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573 1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-neutral-900">Parámetros</h3>
                </div>
                <button wire:click="clear"
                    class="text-[10px] font-mono text-neutral-400 hover:text-red-500 bg-neutral-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-neutral-200 transition-all uppercase">
                    Limpiar
                </button>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">Modelo de Cola</label>
                    <select wire:model.live="model" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700">
                        <option value="MM1">M/M/1 — Servidor único</option>
                        <option value="MMs">M/M/s — Múltiples servidores</option>
                        <option value="MG1">M/G/1 — Tiempos generales</option>
                        <option value="MM1K">M/M/1/K — Capacidad limitada</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">
                            Lambda (<span class="font-serif italic text-xs">λ</span>)
                        </label>
                        <input type="number" step="0.01" wire:model="lambda" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">
                            Mu (<span class="font-serif italic text-xs">μ</span>)
                        </label>
                        <input type="number" step="0.01" wire:model="mu" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                </div>

                @if($model == 'MMs')
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-blue-600 uppercase mb-2">Servidores (s)</label>
                        <input type="number" wire:model="s" class="w-full p-3 bg-blue-50 border border-blue-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                @endif

                @if($model == 'MG1')
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-purple-600 uppercase mb-2">
                            Varianza (<span class="font-serif italic text-xs">σ²</span>)
                        </label>
                        <input type="number" step="0.0001" wire:model="sigma2" class="w-full p-3 bg-purple-50 border border-purple-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                @endif

                @if($model == 'MM1K')
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-amber-600 uppercase mb-2">Capacidad (K)</label>
                        <input type="number" wire:model="k" class="w-full p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>
                @endif

                <button wire:click="calculate" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 rounded-xl shadow-sm uppercase tracking-wider text-sm mt-2 transition-transform active:scale-95">
                    Calcular Parámetros
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if($results)
            <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-sm font-mono font-bold text-neutral-500 uppercase tracking-wider">Resultados <span class="text-cyan-600">{{ $model }}</span></h3>
                    <div class="text-[10px] font-mono bg-neutral-900 text-white px-3 py-1 rounded-full uppercase">Tendencia del Sistema</div>
                </div>

                <div class="mb-8 bg-neutral-50 rounded-xl p-4 border border-neutral-100 h-[280px]">
                    <canvas id="queuingChart"></canvas>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    <div class="bg-cyan-50 rounded-xl p-4 border border-cyan-100">
                        <span class="block text-[10px] font-mono font-bold text-cyan-600 uppercase mb-1">Utilización (ρ)</span>
                        <span class="text-lg font-bold text-cyan-800 font-mono">{{ number_format($results['rho']*100, 2) }}%</span>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                        <span class="block text-[10px] font-mono font-bold text-purple-600 uppercase mb-1">Prob. Espera (Pw)</span>
                        <span class="text-lg font-bold text-purple-800 font-mono">{{ number_format($results['Pw']*100, 2) }}%</span>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                        <span class="block text-[10px] font-mono font-bold text-emerald-600 uppercase mb-1">Clientes Sist (Ls)</span>
                        <span class="text-lg font-bold text-emerald-800 font-mono">{{ number_format($results['L'], 4) }}</span>
                    </div>
                </div>

                <div class="bg-neutral-50 rounded-xl p-5 border border-neutral-200">
                    <h4 class="text-xs font-mono font-bold text-neutral-600 uppercase tracking-wider mb-4">Desarrollo Matemático Paso a Paso</h4>
                    <div class="bg-white rounded-lg border border-neutral-100 mb-2 overflow-x-auto scrollbar-thin shadow-sm">
                        <div id="katex-formula" class="p-8 flex justify-start text-lg md:text-xl items-center min-w-max">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-900 px-5 py-3 text-white text-xs font-mono font-bold uppercase tracking-wider">Historial de Cálculos</div>
                @if(count($history) > 0)
                <div class="overflow-y-auto max-h-[400px] scrollbar-thin">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($history as $calc)
                            <tr wire:click="loadCalculation({{ $calc->id }})" class="hover:bg-cyan-50 cursor-pointer transition-all">
                                <td class="px-5 py-3 text-[10px] font-mono font-bold text-cyan-600">{{ $calc->model_type }}</td>
                                <td class="px-5 py-3 text-xs font-mono text-neutral-600">λ: {{ $calc->inputs['lambda'] }} · μ: {{ $calc->inputs['mu'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    let myChart = null;

    function updateUI(results, model, params) {
        if (!results || Object.keys(results).length === 0) return;

        const formulaDiv = document.getElementById('katex-formula');
        if (formulaDiv) {
            const { l, m, s, v, k } = params;
            let latex = "";

            const L = parseFloat(l || 0).toFixed(3);
            const M = parseFloat(m || 0).toFixed(3);
            const R = parseFloat(results.rho || 0).toFixed(4);
            const PW = parseFloat(results.Pw || 0).toFixed(4);
            const LQ = parseFloat(results.Lq || 0).toFixed(4);
            const LS = parseFloat(results.L || 0).toFixed(4);
            const WQ = parseFloat(results.Wq || 0).toFixed(4);
            const WS = parseFloat(results.W || 0).toFixed(4);
            const intensity = (M > 0) ? (L / M).toFixed(4) : "0.0000";
            const P0_REAL = results.P0 ? parseFloat(results.P0).toFixed(4) : "0.0000";

            switch (model) {
                case 'MM1':
                    latex = `\\begin{aligned} 
                    &\\text{1. Utilización: } \\rho = \\frac{\\lambda}{\\mu} = \\frac{${L}}{${M}} = ${R} \\\\[10pt]
                    &\\text{2. Prob. Espera: } P_w = \\rho = ${R} \\\\[10pt]
                    &\\text{3. Clientes Cola: } L_q = \\frac{\\lambda^2}{\\mu(\\mu - \\lambda)} = \\frac{${L}^2}{${M}(${M} - ${L})} = ${LQ} \\\\[10pt]
                    &\\text{4. Clientes Sist: } L_s = \\frac{\\lambda}{\\mu - \\lambda} = \\frac{${L}}{${M} - ${L}} = ${LS} \\\\[10pt]
                    &\\text{5. Tiempo Cola: } W_q = \\frac{L_q}{\\lambda} = \\frac{${LQ}}{${L}} = ${WQ} \\text{ h} \\\\[10pt]
                    &\\text{6. Tiempo Sist: } W_s = \\frac{L_s}{\\lambda} = \\frac{${LS}}{${L}} = ${WS} \\text{ h}
                    \\end{aligned}`;
                    break;

                case 'MMs':
                    let sumSust = "";
                    let limitS = parseInt(s || 1);
                    for (let n = 0; n < limitS; n++) {
                        let f = 1; for (let i = 1; i <= n; i++) f *= i;
                        sumSust += `\\frac{${intensity}^{${n}}}{${f}}`;
                        if (n < limitS - 1) sumSust += " + ";
                    }

                    latex = `\\begin{aligned} 
                    &\\text{1. Intensidad: } r = \\frac{\\lambda}{\\mu} = \\frac{${L}}{${M}} = ${intensity} \\\\[10pt]
                    &\\text{2. Utilización: } \\rho = \\frac{r}{s} = \\frac{${intensity}}{${s}} = ${R} \\\\[10pt]
                    &\\text{3. Prob. Vacío (} P_0 \\text{):} \\\\[6pt]
                    & P_0 = \\left[ \\sum_{n=0}^{s-1} \\frac{r^n}{n!} + \\frac{r^s}{s!(1 - \\rho)} \\right]^{-1} \\\\[10pt]
                    & P_0 = \\left[ \\left( ${sumSust} \\right) + \\frac{${intensity}^{${limitS}}}{${limitS}!(1 - ${R})} \\right]^{-1} \\\\[10pt]
                    & P_0 = ${P0_REAL} \\\\[14pt]
                    &\\text{4. Espera (} P_w \\text{): } \\\\[6pt]
                    & P_w = \\frac{r^s \\cdot P_0}{s! (1 - \\rho)} = \\frac{${intensity}^{${limitS}} \\cdot ${P0_REAL}}{${limitS}! (1 - ${R})} = ${PW} \\\\[12pt]
                    &\\text{5. Clientes Cola: } L_q = \\frac{P_w \\cdot \\rho}{1 - \\rho} = \\frac{${PW} \\cdot ${R}}{1 - ${R}} = ${LQ} \\\\[10pt]
                    &\\text{6. Clientes Sist: } L_s = L_q + r = ${LQ} + ${intensity} = ${LS} \\\\[10pt]
                    &\\text{7. Tiempo Cola: } W_q = \\frac{L_q}{\\lambda} = \\frac{${LQ}}{${L}} = ${WQ} \\text{ h} \\\\[10pt]
                    &\\text{8. Tiempo Sist: } W_s = W_q + \\frac{1}{\\mu} = ${WQ} + \\frac{1}{${M}} = ${WS} \\text{ h}
                    \\end{aligned}`;
                    break;

                case 'MG1':
                    latex = `\\begin{aligned} 
                    &\\text{1. Utilización: } \\rho = \\frac{\\lambda}{\\mu} = \\frac{${L}}{${M}} = ${R} \\\\[10pt]
                    &\\text{2. Clientes Cola: } L_q = \\frac{\\lambda^2 \\sigma^2 + \\rho^2}{2(1 - \\rho)} = \\frac{${L}^2 \\cdot ${v} + ${R}^2}{2(1 - ${R})} = ${LQ} \\\\[10pt]
                    &\\text{3. Tiempo Cola: } W_q = \\frac{L_q}{\\lambda} = \\frac{${LQ}}{${L}} = ${WQ} \\text{ h} \\\\[10pt]
                    &\\text{4. Tiempo Sist: } W_s = W_q + \\frac{1}{\\mu} = ${WQ} + \\frac{1}{${M}} = ${WS} \\text{ h} \\\\[10pt]
                    &\\text{5. Clientes Sist: } L_s = \\lambda \\cdot W_s = ${L} \\cdot ${WS} = ${LS}
                    \\end{aligned}`;
                    break;

                case 'MM1K':
                    latex = `\\begin{aligned} 
                    &\\text{1. Prob. Vacío: } P_0 = \\frac{1 - r}{1 - r^{K+1}} = \\frac{1 - ${intensity}}{1 - ${intensity}^{${parseInt(k)+1}}} = ${P0_REAL} \\\\[10pt]
                    &\\text{2. Prob. Bloqueo: } P_K = P_0 \\cdot r^K = ${P0_REAL} \\cdot ${intensity}^{${k}} = ${PW} \\\\[10pt]
                    &\\text{3. Llegada Efec: } \\lambda_{e} = \\lambda(1 - P_K) = ${L}(1 - ${PW}) = ${(L * (1 - PW)).toFixed(4)} \\\\[10pt]
                    &\\text{4. Clientes Sist: } L_s = \\text{Resultado Final} = ${LS} \\\\[10pt]
                    &\\text{5. Tiempo Sist: } W_s = \\frac{L_s}{\\lambda_{e}} = \\frac{${LS}}{${(L * (1 - PW)).toFixed(4)}} = ${WS} \\text{ h} \\\\[10pt]
                    &\\text{6. Tiempo Cola: } W_q = W_s - \\frac{1}{\\mu} = ${WS} - \\frac{1}{${M}} = ${WQ} \\text{ h}
                    \\end{aligned}`;
                    break;
            }
            if (latex !== "") {
                katex.render(latex, formulaDiv, { throwOnError: false, displayMode: true });
            }
        }

        const ctx = document.getElementById('queuingChart');
        if (ctx) {
            if (myChart) myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ocupación (ρ)', 'Espera (Pw)', 'Clientes (Lq)', 'Tiempo (Ws)'],
                    datasets: [{
                        label: 'Métricas Actuales',
                        data: [results.rho, results.Pw, results.Lq, results.W],
                        borderColor: '#0891b2',
                        backgroundColor: 'rgba(8, 145, 178, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointBackgroundColor: '#0891b2'
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    document.addEventListener('livewire:initialized', () => {
        const res = @json($results);
        if (res) {
            updateUI(res, @json($model), {
                l: @json($lambda), m: @json($mu), s: @json($s), v: @json($sigma2), k: @json($k)
            });
        }

        window.addEventListener('calculate-updated', event => {
            const detail = event.detail[0] || event.detail;
            setTimeout(() => {
                updateUI(detail.results, detail.model, detail.params);
            }, 100);
        });
    });
</script>
</div>

<style>
    .scrollbar-thin::-webkit-scrollbar { height: 8px; width: 6px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: #f5f5f5; border-radius: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #c2c2c2; }
</style>