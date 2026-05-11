<div class="p-4 max-w-7xl mx-auto px-6 py-10">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>

    <div class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('dashboard') }}" class="text-xs font-mono text-neutral-400 hover:text-neutral-600 transition-colors flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span>volver</span>
            </a>
            <span class="text-neutral-300">|</span>
            <span class="text-xs font-mono text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">módulo / 04</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-neutral-900">
            Analizador <span class="text-amber-600">Estadístico TDS</span>
        </h1>
        <p class="text-sm font-mono text-neutral-500 mt-2">
            Validación de Aleatoriedad — Medias · Varianza · Chi-Cuadrada · Corridas · Póker
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-neutral-200 h-fit">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-neutral-900 uppercase text-xs tracking-widest font-mono">Entrada</h3>
                    </div>
                    <button wire:click="limpiar"
                        class="text-[10px] font-mono text-neutral-400 hover:text-red-500 bg-neutral-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-neutral-200 transition-all uppercase">
                        Limpiar
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="p-2 bg-indigo-50 border border-indigo-100 rounded-xl text-[10px] font-mono font-bold text-indigo-600 uppercase mb-4 text-center">
                            Nivel de Confianza: 95%
                        </div>
                        <label class="block text-[10px] font-mono font-bold text-amber-600 uppercase mb-2 tracking-widest">Datos Ri (Serie)</label>
                        <textarea wire:model="input_data" 
                            class="w-full h-48 p-3 bg-amber-50/30 border border-amber-100 rounded-xl text-xs font-mono text-neutral-700 custom-scrollbar focus:ring-2 focus:ring-amber-500 outline-none"
                            placeholder="0.8797, 0.3884, 0.6289..."></textarea>
                        <p class="text-[9px] text-neutral-400 mt-2 italic font-mono">Soporta espacios, comas y saltos de línea.</p>
                    </div>

                    <button wire:click="procesar"
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-4 rounded-xl shadow-sm uppercase tracking-wider text-sm mt-2 transition-transform active:scale-95">
                        EJECUTAR ANÁLISIS
                    </button>

                    @if(session()->has('error'))
                    <p class="text-[10px] text-red-500 font-bold italic bg-red-50 p-3 rounded-xl border border-red-100">{{ session('error') }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-neutral-200 text-xs text-left">
                <div class="bg-neutral-900 px-5 py-3 text-white font-bold uppercase text-[10px] tracking-widest text-center font-mono italic">Historial Analizador</div>
                <div class="h-[250px] overflow-y-auto custom-scrollbar bg-neutral-50/50">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-neutral-100">
                            @forelse($historial as $item)
                            <tr wire:click="cargarHistorial({{ $item->id }})" class="hover:bg-amber-50 bg-white cursor-pointer transition-colors">
                                <td class="px-5 py-4">
                                    <p class="font-bold text-amber-600 uppercase text-[10px]">{{ $item->nombre_set ?? 'Muestra N='.$item->n }}</p>
                                    <p class="text-neutral-400 text-[9px] font-mono uppercase mt-1">{{ $item->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <svg class="w-4 h-4 text-neutral-200 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </td>
                            </tr>
                            @empty
                            <tr><td class="p-4 text-center text-neutral-400 font-mono italic">Sin registros</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">
            @if(!empty($resultados))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($resultados as $key => $res)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-neutral-200 flex flex-col justify-between transition-hover hover:shadow-md">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xs font-black text-indigo-700 uppercase tracking-widest font-mono">{{ $res['titulo'] }}</h4>
                                    <div class="mt-2 inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $res['pasa'] ? 'bg-green-50 text-green-600 border border-green-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                        {{ $res['pasa'] ? 'Hipótesis Aceptada' : 'Hipótesis Rechazada' }}
                                    </div>
                                </div>
                                <div class="bg-indigo-50 p-2 rounded-lg border border-indigo-100">
                                    <span class="text-[10px] font-mono font-bold text-indigo-600 italic uppercase">Literal</span>
                                </div>
                            </div>

                            <div class="bg-neutral-50 rounded-xl p-4 flex justify-center mb-6 border border-neutral-100 h-24 items-center overflow-hidden">
                                <div class="render-latex text-lg text-neutral-700" data-formula="{{ $res['formula'] }}"></div>
                            </div>

                            <div class="space-y-3">
                                <p class="text-[10px] font-mono font-bold text-neutral-400 uppercase tracking-widest">Desarrollo del Análisis:</p>
                                <div class="bg-neutral-50/50 rounded-xl p-4 space-y-2 border border-neutral-100">
                                    @foreach($res['pasos'] as $paso)
                                    <div class="flex items-center text-xs font-mono text-neutral-600 border-b border-neutral-100 last:border-0 pb-1">
                                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full mr-3"></span>
                                        {{ $paso }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-neutral-200">
                    <div class="bg-neutral-900 px-6 py-3 text-white font-mono font-bold uppercase flex justify-between items-center text-xs tracking-wider">
                        <span>Serie de Datos Ri (n={{ count($numeros) }})</span>
                        <span class="bg-amber-500 text-neutral-900 px-2 py-0.5 rounded text-[10px]">Cargado</span>
                    </div>
                    <div class="p-6 grid grid-cols-5 md:grid-cols-10 gap-3 bg-neutral-50/50 overflow-y-auto max-h-64 custom-scrollbar">
                        @foreach($numeros as $index => $n)
                        <div class="bg-white border border-neutral-200 p-2 rounded-xl text-center shadow-sm">
                            <p class="text-[9px] text-neutral-300 font-bold font-mono">#{{ $index + 1 }}</p>
                            <p class="text-[10px] font-mono font-bold text-neutral-700">{{ number_format($n, 4) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border-2 border-dashed border-neutral-200 p-32 text-center shadow-sm">
                    <div class="text-neutral-200 mb-6">
                        <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-neutral-400 font-bold uppercase tracking-widest text-sm font-mono tracking-[0.2em]">Esperando Datos de la Guía</h3>
                    <p class="text-xs text-neutral-400 mt-2 font-mono italic">Pega la serie de números Ri generada para validar los literales del ejercicio.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function renderFormulas() {
            document.querySelectorAll('.render-latex').forEach(el => {
                const formula = el.getAttribute('data-formula');
                if (formula) {
                    try {
                        katex.render(formula, el, {
                            throwOnError: false,
                            displayMode: true
                        });
                    } catch (err) {
                        console.error("Error renderizando KaTeX:", err);
                    }
                }
            });
        }

        document.addEventListener('livewire:initialized', () => {
            renderFormulas();

            window.addEventListener('formulas-ready', () => {
                // Pequeño delay para asegurar que el DOM de Livewire terminó de actualizarse
                setTimeout(renderFormulas, 50);
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #fbbf24; }
    </style>
</div>