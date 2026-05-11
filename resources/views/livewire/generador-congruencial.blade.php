<div class="max-w-7xl mx-auto px-6 py-10">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
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
            <span class="text-xs font-mono text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-full border border-cyan-200">módulo / 01</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-neutral-900">
            Métodos <span class="text-cyan-600">Congruenciales</span>
        </h1>
        <p class="text-sm font-mono text-neutral-500 mt-2">
            Generadores de Números Pseudoaleatorios — Mixto · Lineal · Multiplicativo · Cuadrático
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-1 bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm sticky top-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-neutral-900">Parámetros</h3>
                </div>
                <button wire:click="limpiar" class="text-[10px] font-mono text-neutral-400 hover:text-red-500 bg-neutral-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-neutral-200 transition-all uppercase">
                    Limpiar
                </button>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">Seleccionar Algoritmo</label>
                    <select wire:model.live="metodo" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700">
                        <option value="mixto">Congruencial Mixto</option>
                        <option value="multiplicativo">Congruencial Multiplicativo</option>
                        <option value="aditivo">Algoritmo Aditivo</option>
                        <option value="segundo_orden">Segundo Orden</option>
                        <option value="lineal">Algoritmo Lineal General</option>
                        <option value="cuadratico">Algoritmo Cuadrático</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase mb-2">Semilla (<span class="font-serif italic text-xs">X₀</span>)</label>
                        <input type="number" wire:model="x0" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase mb-2">Módulo (<span class="font-serif italic text-xs">m</span>)</label>
                        <input type="number" wire:model="m" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    @if(!in_array($metodo, ['aditivo']))
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-blue-600 uppercase mb-2">Constante (<span class="font-serif italic text-xs">a</span>)</label>
                        <input type="number" wire:model="a" class="w-full p-3 bg-blue-50 border border-blue-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    @endif
                    @if(in_array($metodo, ['cuadratico', 'segundo_orden']))
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-green-600 uppercase mb-2">Constante (<span class="font-serif italic text-xs">b</span>)</label>
                        <input type="number" wire:model="b" class="w-full p-3 bg-green-50 border border-green-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    @endif
                    @if(in_array($metodo, ['lineal', 'mixto', 'aditivo', 'cuadratico']))
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-purple-600 uppercase mb-2">Incremento (<span class="font-serif italic text-xs">c</span>)</label>
                        <input type="number" wire:model="c" class="w-full p-3 bg-purple-50 border border-purple-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                    @endif
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-cyan-600 uppercase mb-2">Cantidad a Generar</label>
                        <input type="number" wire:model="cantidad" class="w-full p-3 bg-cyan-50 border border-cyan-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    @if($metodo == 'segundo_orden')
                    <div class="col-span-2">
                        <label class="block text-[10px] font-mono font-bold text-orange-600 uppercase mb-2">Semilla Ant. (<span class="font-serif italic text-xs">Xⱼ₋₁</span>)</label>
                        <input type="number" wire:model="x_atras" class="w-full p-3 bg-orange-50 border border-orange-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-orange-500 outline-none">
                    </div>
                    @endif
                </div>

                <button wire:click="generar" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 rounded-xl shadow-sm uppercase tracking-wider text-sm mt-2 transition-transform active:scale-95">
                    GENERAR NÚMEROS
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if(count($resultados) > 0)
            <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-sm font-mono font-bold text-neutral-500 uppercase tracking-wider">Resultados <span class="text-cyan-600">{{ $metodo }}</span></h3>
                    <div class="text-[10px] font-mono bg-neutral-900 text-white px-3 py-1 rounded-full uppercase">Distribución Ri</div>
                </div>

                <div class="mb-8 bg-neutral-50 rounded-xl p-4 border border-neutral-100 h-[280px]">
                    <canvas id="congruencialChart"></canvas>
                </div>

                <div class="bg-neutral-50 rounded-xl p-5 border border-neutral-200">
                    <h4 class="text-xs font-mono font-bold text-neutral-600 uppercase tracking-wider mb-4 text-center">Desarrollo Matemático Paso a Paso</h4>
                    <div class="bg-white rounded-lg border border-neutral-100 mb-2 overflow-x-auto scrollbar-thin shadow-sm">
                        <div id="katex-procedimiento" class="p-8 flex justify-center text-lg min-w-max">
                            <span class="text-neutral-400">Procesando fórmulas...</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-900 px-5 py-3 text-white text-xs font-mono font-bold uppercase tracking-wider">Historial de Algoritmos</div>
                @if(count($historial) > 0)
                <div class="overflow-y-auto max-h-[300px] scrollbar-thin">
                    <table class="min-w-full text-left">
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($historial as $h)
                            <tr wire:click="cargarHistorial({{ $h->id }})" class="hover:bg-cyan-50 cursor-pointer transition-all">
                                <td class="px-5 py-4 font-bold text-cyan-600 uppercase text-xs">{{ $h->metodo }}</td>
                                <td class="px-5 py-4 text-xs font-mono text-neutral-500">
                                    X₀: {{ $h->parametros['x0'] }} | m: {{ $h->parametros['m'] }}
                                </td>
                                <td class="px-5 py-4 text-right text-[10px] text-neutral-400 uppercase font-mono">{{ $h->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .scrollbar-thin::-webkit-scrollbar { height: 8px; width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: #f5f5f5; border-radius: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #c2c2c2; }
    </style>

    <script>
        let congruencialChart = null;

        function renderKaTeX(data) {
            const payload = data.detail ? (data.detail[0] || data.detail) : data;
            const { resultados, metodo, params } = payload;
            
            const container = document.getElementById('katex-procedimiento');
            if(!resultados || !container) return;

            if (typeof katex === 'undefined') {
                setTimeout(() => renderKaTeX(payload), 200);
                return;
            }

            const a = parseInt(params.a) || 0;
            const b = parseInt(params.b) || 0;
            const c = parseInt(params.c) || 0;
            const m = parseInt(params.m) || 1;
            const divisorRi = m - 1;
            
            let latex = "\\begin{array}{c|l|l|c|l|c} " +
                "\\mathbf{i} & \\mathbf{Operación} & \\mathbf{División} & \\mathbf{X_i} & \\mathbf{Cálculo} & \\mathbf{R_i} \\\\ \\hline ";

            resultados.forEach(res => {
                let op = "";
                let valOp = 0;
                let xn = parseInt(res.xn);
                let xn_ant = parseInt(res.xn_anterior) || 0;

                switch(metodo) {
                    case 'mixto': case 'lineal': 
                        op = `${a}(${xn}) + ${c}`; valOp = (a * xn + c); break;
                    case 'multiplicativo': 
                        op = `${a}(${xn})`; valOp = (a * xn); break;
                    case 'aditivo': 
                        op = `${xn} + ${c}`; valOp = (xn + c); break;
                    case 'cuadratico': 
                        op = `${a}(${xn}^2) + ${b}(${xn}) + ${c}`; valOp = (a * Math.pow(xn, 2) + b * xn + c); break;
                    case 'segundo_orden': 
                        op = `${a}(${xn}) + ${b}(${xn_ant})`; valOp = (a * xn + b * xn_ant); break;
                }

                let coc = Math.floor(valOp / m);
                let res_xn = res.proximo_xn;
                
                // Cambiamos \div por el símbolo ÷ directo para evitar errores de escape
                latex += `${res.i} & \\text{${op} = ${valOp}} & \\text{${valOp} ÷ ${m} = ${coc} sob. ${res_xn}} & ${res_xn} & \\dfrac{${res_xn}}{${divisorRi}} & ${res.ri} \\\\[1em] `;
            });

            latex += "\\end{array}";
            
            try {
                katex.render(latex, container, { 
                    throwOnError: false, 
                    displayMode: true
                });
            } catch (err) {
                container.innerHTML = "Error de formato matemático";
            }
        }

        function updateChart(resultados) {
            const ctx = document.getElementById('congruencialChart');
            if (!ctx) return;
            if (congruencialChart) congruencialChart.destroy();
            
            congruencialChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: resultados.map(r => `i=${r.i}`),
                    datasets: [{
                        label: 'Valores Ri',
                        data: resultados.map(r => r.ri),
                        backgroundColor: '#0891b2',
                        borderRadius: 5,
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 1 }
                    }
                }
            });
        }

        document.addEventListener('livewire:initialized', () => {
            @if(count($resultados) > 0)
                renderKaTeX({
                    resultados: @json($resultados),
                    metodo: @json($metodo),
                    params: { a: @json($a), b: @json($b), c: @json($c), m: @json($m) }
                });
                updateChart(@json($resultados));
            @endif

            window.addEventListener('generador-updated', event => {
                renderKaTeX(event);
                updateChart(event.detail[0]?.resultados || event.detail.resultados);
            });
        });
    </script>
</div>