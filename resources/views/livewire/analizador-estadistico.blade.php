<div class="p-4 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-gray-200 h-fit">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700 uppercase tracking-tighter italic">Analizador TDS</h3>
                <button wire:click="$set('input_data', '')"
                    class="text-[10px] bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded text-gray-500 font-bold uppercase transition">Limpiar</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Prueba de la Guía</label>
                    <select wire:model.live="metodo"
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-50 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-amber-400 outline-none">
                        <option value="medias">Prueba de Medias</option>
                        <option value="varianza">Prueba de Varianza</option>
                        <option value="chi">Prueba de Chi-Cuadrada</option>
                        <option value="corridas">Corridas Arriba/Abajo</option>
                        <option value="poker">Prueba de Póker</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-amber-600 uppercase mb-1">Datos de Entrada (Ri)</label>
                    <textarea wire:model="input_data" 
                        class="w-full h-48 p-3 border border-amber-100 rounded-md bg-amber-50 text-xs font-mono text-gray-700 custom-scrollbar focus:ring-2 focus:ring-amber-400 outline-none"
                        placeholder="0.8797, 0.3884, 0.6289..."></textarea>
                    <p class="text-[9px] text-gray-400 mt-1 italic italic">Soporta espacios, comas y saltos de línea.</p>
                </div>

                <button wire:click="procesar"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-lg shadow-md transition-all uppercase tracking-widest text-sm transform active:scale-95">
                    EJECUTAR PRUEBA
                </button>

                @if(session()->has('error'))
                <p class="text-[10px] text-red-500 font-bold italic bg-red-50 p-2 rounded border border-red-100">{{ session('error') }}</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-3 space-y-6">
            @if(!empty($resultados))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($resultados as $key => $res)
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 transition-hover hover:shadow-md">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-xs font-black text-indigo-700 uppercase tracking-widest">Prueba de {{ $key }}</h4>
                                <div class="mt-2 inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $res['pasa'] ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                                    {{ $res['pasa'] ? 'Hipótesis Aceptada' : 'Hipótesis Rechazada' }}
                                </div>
                            </div>
                            <div class="bg-indigo-50 p-2 rounded-lg border border-indigo-100">
                                <span class="text-[10px] font-mono font-bold text-indigo-600 italic">PASO A PASO</span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 text-center mb-4 border border-gray-100">
                            <code class="text-sm font-serif italic text-gray-600">{{ $res['formula'] }}</code>
                        </div>

                        <div class="space-y-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Desarrollo:</p>
                            <div class="bg-indigo-50/30 rounded-lg p-3 space-y-1">
                                @foreach($res['pasos'] as $paso)
                                <div class="flex items-center text-[11px] text-gray-600 border-b border-indigo-50 last:border-0 pb-1">
                                    <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                                    {{ $paso }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                    <div class="bg-gray-800 px-4 py-2 text-white font-bold uppercase flex justify-between items-center text-[10px]">
                        <span>Serie de Datos Procesada (n={{ count($numeros) }})</span>
                    </div>
                    <div class="p-4 grid grid-cols-5 md:grid-cols-10 gap-2 bg-gray-50">
                        @foreach($numeros as $index => $n)
                        <div class="bg-white border border-gray-200 p-1 rounded text-center">
                            <p class="text-[8px] text-gray-300 font-bold">#{{ $index + 1 }}</p>
                            <p class="text-[10px] font-mono text-gray-700">{{ number_format($n, 4) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-20 text-center">
                    <div class="text-gray-300 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-gray-400 font-bold uppercase tracking-widest text-sm">Esperando Datos de la Guía</h3>
                    <p class="text-xs text-gray-400 mt-2">Pega la serie de números Ri para comenzar el análisis estadístico.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</div>