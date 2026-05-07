<div class="p-4 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-gray-200 h-fit">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700 uppercase tracking-tighter">Congruenciales</h3>
                <button wire:click="limpiar"
                    class="text-xs bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded text-gray-600 font-bold uppercase">Limpiar</button>
            </div>

            <div class="space-y-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase">Seleccionar Algoritmo</label>
                <select wire:model.live="metodo"
                    class="w-full p-2 border border-gray-300 rounded-md bg-gray-50 text-sm font-bold text-gray-700">
                    <option value="lineal">Algoritmo Lineal</option>
                    <option value="multiplicativo">Congruencial Multiplicativo</option>
                    <option value="aditivo">Algoritmo Aditivo</option>
                    <option value="cuadratico">Algoritmo Cuadrático</option>
                    <option value="blum_blum">Blum Blum Shub</option>
                    <option value="no_lineal">Algoritmo No Lineal</option>
                </select>

                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Semilla (X0)</label>
                        <input type="number" wire:model="x0"
                            class="w-full p-2 border rounded-md text-sm border-gray-300" placeholder="Ej: 7">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Módulo (m)</label>
                        <input type="number" wire:model="m" class="w-full p-2 border rounded-md text-sm border-gray-300"
                            placeholder="Ej: 16">
                    </div>

                    @if(!in_array($metodo, ['aditivo', 'blum_blum']))
                    <div>
                        <label class="text-[10px] font-bold text-blue-600 uppercase">Constante (a)</label>
                        <input type="number" wire:model="a"
                            class="w-full p-2 border border-blue-200 rounded-md bg-blue-50 text-sm" placeholder="Ej: 5">
                    </div>
                    @endif

                    @if($metodo == 'cuadratico')
                    <div>
                        <label class="text-[10px] font-bold text-green-600 uppercase">Constante (b)</label>
                        <input type="number" wire:model="b"
                            class="w-full p-2 border border-green-200 rounded-md bg-green-50 text-sm"
                            placeholder="Ej: 1">
                    </div>
                    @endif

                    @if(in_array($metodo, ['lineal', 'cuadratico', 'no_lineal']))
                    <div>
                        <label class="text-[10px] font-bold text-purple-600 uppercase">Incremento (c)</label>
                        <input type="number" wire:model="c"
                            class="w-full p-2 border border-purple-100 rounded-md bg-purple-50 text-sm"
                            placeholder="Ej: 3">
                    </div>
                    @endif

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Cantidad a Generar</label>
                        <input type="number" wire:model="cantidad"
                            class="w-full p-2 border rounded-md text-sm border-gray-300" placeholder="Ej: 10">
                    </div>
                </div>

                <button wire:click="generar"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition uppercase tracking-widest text-sm">
                    GENERAR NÚMEROS
                </button>

                @if(session()->has('error'))
                <p class="text-[10px] text-red-500 font-bold italic">{{ session('error') }}</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            @if(count($resultados) > 0)
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">

                <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100 mb-6">
                    <h4 class="text-xs font-bold text-indigo-700 uppercase mb-2">Desarrollo Matemático</h4>
                    <div class="h-64 overflow-y-scroll space-y-1 pr-2 custom-scrollbar">
                        @foreach($resultados as $r)
                        <p class="text-[11px] font-mono text-gray-700 border-b border-indigo-100 py-1">
                            <span class="font-bold text-blue-600">i={{ $r['i'] }}:</span> {!! $r['detalle'] !!}
                        </p>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-100 overflow-hidden text-xs">
                    <table class="min-w-full">
                        <thead class="bg-gray-800 text-white uppercase text-[9px]">
                            <tr>
                                <th class="p-2">i</th>
                                <th class="p-2 text-center">Xn (Actual)</th>
                                <th class="p-2 text-center">Xn+1 (Siguiente)</th>
                                <th class="p-2 text-right">Ri (Xn+1 / m)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($resultados as $r)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="p-2 text-gray-400 border-r">{{ $r['i'] }}</td>
                                <td class="p-2 text-center font-mono text-gray-500 italic">{{ $r['xn'] }}</td>
                                <td class="p-2 text-center font-bold text-indigo-700 text-sm">{{ $r['proximo_xn'] }}
                                </td>
                                <td class="p-2 text-right font-bold text-green-600 text-sm">{{ $r['ri'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 text-xs text-left">
                <div class="bg-gray-800 px-4 py-2 text-white font-bold uppercase flex justify-between items-center">
                    <span>Historial de Algoritmos</span>
                    <span class="text-[9px] text-gray-400 italic">Desliza para ver más</span>
                </div>
                <div class="h-[300px] overflow-y-scroll custom-scrollbar bg-gray-50">
                    <table class="min-w-full text-xs text-left">
                        <tbody class="divide-y divide-gray-200">
                            @foreach($historial as $h)
                            <tr class="hover:bg-indigo-100 bg-white cursor-pointer transition">
                                <td class="p-3 font-bold text-indigo-600 uppercase">{{ $h->metodo }}</td>
                                <td class="p-3 text-gray-600">
                                    <span class="font-bold">X0:</span> {{ $h->parametros['x0'] }} |
                                    <span class="font-bold">m:</span> {{ $h->parametros['m'] }}
                                </td>
                                <td class="p-3 text-right text-gray-400 text-[10px]">{{ $h->created_at->diffForHumans()
                                    }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .custom-scrollbar {
            scrollbar-width: thin;
        }
    </style>
</div>