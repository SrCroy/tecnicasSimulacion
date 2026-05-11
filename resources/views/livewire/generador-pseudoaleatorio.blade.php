<div class="max-w-7xl mx-auto px-6 py-10">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('dashboard') }}" class="text-xs font-mono text-neutral-400 hover:text-neutral-600 transition-colors flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>volver</span>
            </a>
            <span class="text-neutral-300">|</span>
            <span class="text-xs font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">módulo / 03</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-neutral-900">
            Métodos <span class="text-blue-600">Pseudoaleatorios</span>
        </h1>
        <p class="text-sm font-mono text-neutral-500 mt-2">
            Generadores No Congruenciales — Cuadrados Medios · Productos Medios · Constante
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-1 bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm sticky top-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-neutral-900 uppercase text-xs tracking-widest">Configuración</h3>
                </div>
                <button wire:click="limpiar" class="text-[10px] font-mono text-neutral-400 hover:text-red-500 bg-neutral-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-neutral-200 transition-all uppercase">
                    Limpiar
                </button>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">Seleccionar Algoritmo</label>
                    <select wire:model.live="metodo" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700 outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="cuadrados">Cuadrados Medios</option>
                        <option value="productos">Productos Medios</option>
                        <option value="constante">Valor Constante</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase mb-2">Semilla Principal (X₀)</label>
                        <input type="number" wire:model="semilla1" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ej: 5735">
                    </div>

                    @if($metodo == 'productos')
                    <div class="animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="block text-[10px] font-mono font-bold text-blue-600 uppercase mb-2">Semilla Secundaria (X₁)</label>
                        <input type="number" wire:model="semilla2" class="w-full p-3 bg-blue-50 border border-blue-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    @endif

                    @if($metodo == 'constante')
                    <div class="animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="block text-[10px] font-mono font-bold text-purple-600 uppercase mb-2">Constante (a)</label>
                        <input type="number" wire:model="constante_a" class="w-full p-3 bg-purple-50 border border-purple-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase mb-2">Cantidad a Generar</label>
                        <input type="number" wire:model="cantidad" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <button wire:click="generar" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-sm uppercase tracking-wider text-sm mt-2 transition-transform active:scale-95">
                    GENERAR NÚMEROS
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if(count($resultados) > 0)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 font-mono">
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm text-center">
                    <p class="text-[10px] font-bold text-neutral-400 uppercase mb-1">Chi-Cuad. Calc.</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $chi_calculado }}</p>
                </div>
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm text-center">
                    <p class="text-[10px] font-bold text-neutral-400 uppercase mb-1">Valor Crítico</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $chi_critico }}</p>
                </div>
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm text-center">
                    <p class="text-[10px] font-bold text-neutral-400 uppercase mb-1">Aceptación</p>
                    <p class="text-xl font-bold text-neutral-700">90%</p>
                </div>
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm text-center flex flex-col justify-center items-center">
                    <p class="text-[10px] font-bold text-neutral-400 uppercase mb-1">Conclusión</p>
                    <span class="text-[10px] font-bold px-3 py-1 rounded-full border {{ $pasa_prueba ? 'text-green-600 bg-green-50 border-green-200' : 'text-red-600 bg-red-50 border-red-200' }} uppercase tracking-tighter">
                        {{ $pasa_prueba ? 'Uniforme' : 'No Uniforme' }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
                <div class="mb-8 bg-neutral-50 rounded-xl p-4 border border-neutral-100 h-[280px]">
                    <canvas id="pseudoChart"></canvas>
                </div>

                <div class="mb-8 p-5 bg-neutral-900 rounded-2xl border border-neutral-800 shadow-inner">
                    <h4 class="text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Análisis de Uniformidad (Chi-Cuadrado)
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-[11px] font-mono text-neutral-300">
                            <thead>
                                <tr class="text-neutral-500 border-b border-neutral-800">
                                    <th class="pb-2 text-left">Intervalo</th>
                                    <th class="pb-2 text-center">Obs (Oi)</th>
                                    <th class="pb-2 text-center">Esp (Ei)</th>
                                    <th class="pb-2 text-right">(Oi-Ei)² / Ei</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-800">
                                @php $intervalos = ['[0.0 - 0.2)', '[0.2 - 0.4)', '[0.4 - 0.6)', '[0.6 - 0.8)', '[0.8 - 1.0]']; @endphp
                                @foreach($frecuencias as $index => $oi)
                                <tr>
                                    <td class="py-2 text-neutral-400">{{ $intervalos[$index] }}</td>
                                    <td class="py-2 text-center font-bold">{{ $oi }}</td>
                                    <td class="py-2 text-center">{{ count($resultados) / 5 }}</td>
                                    <td class="py-2 text-right text-blue-400">
                                        {{ number_format(pow($oi - (count($resultados) / 5), 2) / (count($resultados) / 5), 4) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-neutral-700 font-bold">
                                    <td colspan="3" class="pt-2 text-right text-neutral-500 uppercase text-[10px]">Total Chi-Calculado:</td>
                                    <td class="pt-2 text-right text-blue-500 text-sm">{{ $chi_calculado }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-neutral-50 rounded-xl p-5 border border-neutral-200">
                    <h4 class="text-xs font-mono font-bold text-neutral-600 uppercase tracking-wider mb-4 text-center text-gray-500 font-bold">Desarrollo Matemático Paso a Paso</h4>
                    <div class="bg-white rounded-lg border border-neutral-100 overflow-x-auto scrollbar-thin shadow-sm">
                        <div id="container-final-pseudo" class="p-4"></div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden text-xs">
                <div class="bg-neutral-900 px-5 py-3 text-white font-bold uppercase tracking-wider">Historial de Generaciones</div>
                @if(count($historial) > 0)
                <div class="overflow-y-auto max-h-[300px] scrollbar-thin bg-gray-50">
                    <table class="min-w-full text-left font-mono">
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($historial as $h)
                            <tr wire:click="cargarHistorial({{ $h->id }})" class="hover:bg-blue-50 cursor-pointer bg-white transition-all">
                                <td class="px-5 py-4 font-bold text-blue-600 uppercase">{{ $h->metodo }}</td>
                                <td class="px-5 py-4 text-neutral-500">
                                    X₀: {{ $h->parametros['s1'] }} | N: {{ count($h->lista_numeros) }}
                                </td>
                                <td class="px-5 py-4 text-right text-neutral-400 italic">
                                    {{ $h->created_at->diffForHumans() }}
                                </td>
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
        let pseudoChart = null;

        function formatOperacion(txt_op) {
            return txt_op
                .replace(/(\d+)\^2/g, '$1<sup>2</sup>')
                .replace(/\*/g, ' &times; ');
        }

        function renderProceso(data) {
            const el = document.getElementById('container-final-pseudo');
            if (!el) return;

            const raw = data.detail ? (data.detail[0] || data.detail) : data;
            if (!raw.resultados || raw.resultados.length === 0) return;

            let html = `
                <table style="width:100%; min-width:650px; border-collapse:collapse; font-family:'Courier New', monospace; font-size:13px;">
                    <thead>
                        <tr style="background:#1e293b; color:#f8fafc; font-size:11px; text-transform:uppercase; letter-spacing:0.05em;">
                            <th style="padding:12px; border:1px solid #475569; text-align:center;">i</th>
                            <th style="padding:12px; border:1px solid #475569; text-align:center;">Operación</th>
                            <th style="padding:12px; border:1px solid #475569; text-align:center;">Valor Ajustado</th>
                            <th style="padding:12px; border:1px solid #475569; text-align:center;">Xi+1</th>
                            <th style="padding:12px; border:1px solid #475569; text-align:center;">Ri+1</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            raw.resultados.forEach((res, idx) => {
                const f = String(res.resultado_full);
                const c = String(res.xi);
                const p = f.indexOf(c);
                const p1 = f.substring(0, p);
                const p3 = f.substring(p + c.length);

                const valorAjustado = `${p1}<span style="color:#2563eb; font-weight:bold; background:rgba(37, 99, 235, 0.05); padding:1px 3px; border-radius:2px; border-bottom:2px solid #2563eb;">${c}</span>${p3}`;
                const bg = idx % 2 === 0 ? '#f8fafc' : '#ffffff';
                const op = formatOperacion(String(res.txt_op));

                html += `
                    <tr style="background:${bg};">
                        <td style="padding:10px; border:1px solid #e2e8f0; text-align:center; color:#94a3b8;">${res.i}</td>
                        <td style="padding:10px; border:1px solid #e2e8f0; text-align:center;">${op} = <b style="color:#334155;">${res.resultado_op}</b></td>
                        <td style="padding:10px; border:1px solid #e2e8f0; text-align:center; letter-spacing:0.1em;">${valorAjustado}</td>
                        <td style="padding:10px; border:1px solid #e2e8f0; text-align:center; font-weight:bold; color:#1e3a5f;">${c}</td>
                        <td style="padding:10px; border:1px solid #e2e8f0; text-align:center; font-weight:bold; color:#16a34a;">${res.ri}</td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;
            el.innerHTML = html;
        }

        function drawChart(res) {
            const ctx = document.getElementById('pseudoChart');
            if (!ctx) return;
            if (pseudoChart) pseudoChart.destroy();
            pseudoChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: res.map(r => `i=${r.i}`),
                    datasets: [{
                        label: 'Valor de Ri',
                        data: res.map(r => r.ri),
                        backgroundColor: '#2563eb',
                        borderRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, max: 1 } }
                }
            });
        }

        document.addEventListener('livewire:initialized', () => {
            @if(count($resultados) > 0)
                renderProceso({ resultados: @json($resultados) });
                drawChart(@json($resultados));
            @endif

            window.addEventListener('pseudo-updated', event => {
                const det = event.detail[0] || event.detail;
                renderProceso(det);
                drawChart(det.resultados);
            });
        });
    </script>

    <style>
        .scrollbar-thin::-webkit-scrollbar { height: 8px; width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: #f5f5f5; border-radius: 4px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 4px; }
    </style>
</div>