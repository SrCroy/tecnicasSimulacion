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
                    <h3 class="font-semibold text-neutral-900 font-mono text-sm uppercase tracking-wider">Parámetros</h3>
                </div>
                <button wire:click="limpiar" class="text-[10px] font-mono text-neutral-400 hover:text-red-500 bg-neutral-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-neutral-200 transition-all uppercase">
                    Limpiar
                </button>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">Seleccionar Algoritmo</label>
                    <select wire:model.live="metodo" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700 focus:ring-2 focus:ring-cyan-500 outline-none">
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
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase mb-2">Semilla (X₀)</label>
                        <input type="number" wire:model="x0" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase mb-2">Módulo (m)</label>
                        <input type="number" wire:model="m" class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-blue-600 uppercase mb-2">Constante (a)</label>
                        <input type="number" wire:model="a" class="w-full p-3 bg-blue-50 border border-blue-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-[10px] font-mono font-bold text-cyan-600 uppercase mb-2">Cantidad (n)</label>
                        <input type="number" wire:model="cantidad" class="w-full p-3 bg-cyan-50 border border-cyan-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>
                </div>

                <button wire:click="generar" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 rounded-xl shadow-sm uppercase tracking-wider text-sm mt-2 transition-transform active:scale-95 font-mono">
                    GENERAR NÚMEROS
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            @if(count($resultados) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm">
                    <p class="text-[10px] font-mono font-bold text-neutral-400 uppercase mb-1">Periodo Detectado</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $periodo > 0 ? $periodo : 'Completo' }}</p>
                </div>
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm">
                    <p class="text-[10px] font-mono font-bold text-neutral-400 uppercase mb-1">Media (x̄)</p>
                    <p class="text-2xl font-bold text-cyan-600">{{ number_format($media, 4) }}</p>
                </div>
                <div class="bg-white border border-neutral-200 p-4 rounded-2xl shadow-sm">
                    <p class="text-[10px] font-mono font-bold text-neutral-400 uppercase mb-1">Varianza (σ²)</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($varianza, 4) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white border border-neutral-200 p-5 rounded-2xl shadow-sm">
                    <h4 class="text-[10px] font-mono font-bold text-neutral-400 uppercase mb-4 border-b pb-2 tracking-widest">Validación Uniformidad</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-mono text-neutral-600 uppercase">Z de Medias:</span>
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $media_pasa ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                {{ number_format($media_z, 2) }} {{ $media_pasa ? 'Aceptada' : 'Rechazada' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-mono text-neutral-600 uppercase">Chi-Cuadrada:</span>
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $chi_pasa ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                {{ number_format($chi_calc, 2) }} {{ $chi_pasa ? 'Aceptada' : 'Rechazada' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-5 rounded-2xl shadow-sm">
                    <h4 class="text-[10px] font-mono font-bold text-neutral-400 uppercase mb-4 border-b pb-2 tracking-widest">Independencia (Corridas)</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-mono text-neutral-600 uppercase">Z Corridas (H):</span>
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $corridas_pasa ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                Z = {{ number_format($corridas_z, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-mono text-neutral-600 uppercase">Z Media (Rachas):</span>
                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $corridas_media_pasa ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                Z = {{ number_format($corridas_media_z, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 bg-white border border-neutral-200 p-5 rounded-2xl shadow-sm">
                    <p class="text-[10px] font-mono font-bold text-neutral-400 uppercase mb-4 text-center tracking-[0.2em] italic">Análisis Poker (Dígitos Decimales)</p>
                    <div class="grid grid-cols-4 gap-3">
                        <div class="flex flex-col items-center p-3 bg-neutral-50 rounded-xl border border-neutral-100">
                            <span class="text-[9px] font-mono text-neutral-500 uppercase">Dif.</span>
                            <span class="text-xl font-bold text-neutral-900">{{ $poker['TD'] }}</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-cyan-50 rounded-xl border border-cyan-100">
                            <span class="text-[9px] font-mono text-cyan-600 uppercase">Par</span>
                            <span class="text-xl font-bold text-cyan-700">{{ $poker['1P'] }}</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-purple-50 rounded-xl border border-purple-100">
                            <span class="text-[9px] font-mono text-purple-600 uppercase">2P/T</span>
                            <span class="text-xl font-bold text-purple-700">{{ $poker['2P_T'] }}</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-red-50 rounded-xl border border-red-100">
                            <span class="text-[9px] font-mono text-red-600 uppercase">PK</span>
                            <span class="text-xl font-bold text-red-700">{{ $poker['PK'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-mono font-bold text-neutral-500 uppercase tracking-widest italic">Distribución Espacial Ri</h3>
                    <div class="text-[10px] font-mono bg-neutral-900 text-white px-3 py-1 rounded-full uppercase tracking-tighter">Scatter Plot</div>
                </div>
                <div class="bg-neutral-50 rounded-2xl p-6 border border-neutral-100 h-[300px]">
                    <canvas id="congruencialChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xs font-mono font-bold text-neutral-500 uppercase tracking-widest italic">Desarrollo Paso a Paso</h3>
                    <span class="text-[10px] font-mono text-cyan-600 font-bold">TOTAL: {{ count($resultados) }} FILAS</span>
                </div>
                <div class="bg-neutral-50 rounded-2xl border border-neutral-200 overflow-x-auto overflow-y-auto max-h-[800px] scrollbar-custom">
                    <div id="katex-procedimiento" class="p-8 flex justify-center text-sm min-w-max">
                        </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-900 px-6 py-3 text-white text-[10px] font-mono font-bold uppercase tracking-[0.2em] text-center">Registro de Generaciones</div>
                @if(count($historial) > 0)
                <div class="overflow-y-auto max-h-[250px] scrollbar-custom">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($historial as $h)
                            <tr wire:click="cargarHistorial({{ $h->id }})" class="hover:bg-cyan-50 cursor-pointer transition-all">
                                <td class="px-6 py-4 font-bold text-cyan-600 uppercase text-[10px]">{{ $h->metodo }}</td>
                                <td class="px-6 py-4 text-[10px] font-mono text-neutral-500 uppercase tracking-tighter">
                                    X₀: {{ $h->parametros['x0'] }} | m: {{ $h->parametros['m'] }} | a: {{ $h->parametros['a'] }}
                                </td>
                                <td class="px-6 py-4 text-right text-[9px] text-neutral-400 uppercase font-mono">{{ $h->created_at->diffForHumans() }}</td>
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
        let congruencialChart = null;

        function renderKaTeX(data) {
            const payload = data.detail ? (data.detail[0] || data.detail) : data;
            const { resultados, params } = payload;
            const container = document.getElementById('katex-procedimiento');
            if(!resultados || !container) return;

            let latex = "\\begin{array}{c|l|c|c|l} " +
                "\\mathbf{i} & \\mathbf{Operación} & \\mathbf{X_i} & \\mathbf{R_i} \\\\ \\hline ";

            // Bucle sin recortes: procesa todas las iteraciones
            resultados.forEach(res => {
                latex += `${res.i} & X_{${res.i}} = (${params.a} \\cdot ${res.xn}) \\pmod{${params.m}} & ${res.proximo_xn} & ${res.ri} \\\\[0.4em] `;
            });

            latex += "\\end{array}";
            
            try {
                katex.render(latex, container, { throwOnError: false, displayMode: true });
            } catch (err) { 
                container.innerHTML = "Error en el renderizado matemático"; 
            }
        }

        function updateChart(resultados) {
            const ctx = document.getElementById('congruencialChart');
            if (!ctx) return;
            if (congruencialChart) congruencialChart.destroy();
            
            congruencialChart = new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Independencia de Ri',
                        data: resultados.map(r => ({ x: r.i, y: r.ri })),
                        backgroundColor: 'rgba(8, 145, 178, 0.6)',
                        pointRadius: 4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    scales: { 
                        y: { beginAtZero: true, max: 1, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }

        document.addEventListener('livewire:initialized', () => {
            @if(count($resultados) > 0)
                renderKaTeX({ resultados: @json($resultados), params: { a: @json($a), m: @json($m) } });
                updateChart(@json($resultados));
            @endif

            window.addEventListener('generador-updated', event => {
                const data = event.detail[0] || event.detail;
                renderKaTeX(data);
                updateChart(data.resultados);
            });
        });
    </script>

    <style>
        .scrollbar-custom::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .scrollbar-custom::-webkit-scrollbar-thumb:hover { background: #0891b2; }
    </style>
</div>