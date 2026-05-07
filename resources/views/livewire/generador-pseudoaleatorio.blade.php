<div class="p-4 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- CONFIGURACIÓN -->
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-gray-200 h-fit">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700 uppercase tracking-tighter">Configuración</h3>
                <button wire:click="limpiar"
                    class="text-xs bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded text-gray-600 font-bold uppercase">Limpiar</button>
            </div>

            <div class="space-y-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase">Método de Generación</label>
                <select wire:model.live="metodo" class="w-full p-2 border rounded-md bg-gray-50 text-sm">
                    <option value="cuadrados">Cuadrados Medios</option>
                    <option value="productos">Productos Medios</option>
                    <option value="constante">Valor Constante</option>
                </select>

                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Semilla Principal (X0)</label>
                        <input type="number" wire:model="semilla1" class="w-full p-2 border rounded-md text-sm"
                            placeholder="Ej: 1234">
                    </div>

                    @if($metodo == 'productos')
                    <div>
                        <label class="text-[10px] font-bold text-blue-600 uppercase">Semilla Secundaria (X1)</label>
                        <input type="number" wire:model="semilla2"
                            class="w-full p-2 border border-blue-200 rounded-md bg-blue-50 text-sm">
                    </div>
                    @endif

                    @if($metodo == 'constante')
                    <div>
                        <label class="text-[10px] font-bold text-purple-600 uppercase">Constante (a)</label>
                        <input type="number" wire:model="constante_a"
                            class="w-full p-2 border border-purple-200 rounded-md bg-purple-50 text-sm">
                    </div>
                    @endif

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Cantidad a Generar</label>
                        <input type="number" wire:model="cantidad" class="w-full p-2 border rounded-md text-sm">
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

        <!-- RESULTADOS -->
        <div class="lg:col-span-2 space-y-4">

            @if(count($resultados) > 0)
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">

                <!-- DESARROLLO MATEMÁTICO -->
                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100 mb-6">
                    <h4 class="text-xs font-bold text-yellow-700 uppercase mb-2">Desarrollo Matemático</h4>

                    <!-- SCROLL CORREGIDO -->
                    <div class="h-64 overflow-y-scroll space-y-1 pr-2 custom-scrollbar">
                        @foreach($resultados as $r)
                        <p class="text-[11px] font-mono text-gray-700 border-b border-yellow-100 py-1">
                            <span class="font-bold text-blue-600">i={{ $r['i'] }}:</span>
                            {!! $r['detalle'] ?? 'Sin detalle disponible.' !!}
                        </p>
                        @endforeach
                    </div>
                </div>

                <!-- TABLA -->
                <div class="bg-white rounded-lg border border-gray-100 overflow-hidden text-xs">
                    <table class="min-w-full">
                        <thead class="bg-gray-800 text-white uppercase text-[9px]">
                            <tr>
                                <th class="p-2">i</th>
                                <th class="p-2 text-center">Operación</th>
                                <th class="p-2 text-center">Xi (Centro)</th>
                                <th class="p-2 text-right">Ri (4 dec)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($resultados as $r)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="p-2 font-mono text-gray-400 border-r">{{ $r['i'] }}</td>
                                <td class="p-2 text-center font-mono text-gray-500 italic">{{ $r['operacion'] }}</td>
                                <td class="p-2 text-center font-bold text-blue-700 text-sm">{{ $r['xi'] }}</td>
                                <td class="p-2 text-right font-bold text-green-600 text-sm">{{ $r['ri'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- HISTORIAL -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 text-xs text-left">
                <div class="bg-gray-800 px-4 py-2 text-white font-bold uppercase flex justify-between items-center">
                    <span>Historial de Generaciones</span>
                    <span class="text-[9px] text-gray-400 italic">Desliza para ver más</span>
                </div>

                <!-- SCROLL YA FUNCIONAL -->
                <div class="h-[300px] overflow-y-scroll custom-scrollbar bg-gray-50">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-200">
                            @forelse($historial as $item)
                            <tr wire:click="cargarHistorial({{ $item->id }})"
                                class="hover:bg-blue-100 bg-white cursor-pointer transition">
                                <td class="p-3 font-bold text-blue-600 uppercase">{{ $item->metodo }}</td>
                                <td class="p-3 text-gray-600">
                                    <span class="font-bold">X0:</span> {{ $item->parametros['s1'] }} |
                                    <span class="font-bold">Cant:</span> {{ count($item->lista_numeros) }}
                                </td>
                                <td class="p-3 text-right text-gray-400 italic">
                                    {{ $item->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-10 text-center text-gray-400 italic">No hay historial
                                    disponible</td>
                            </tr>
                            @endforelse
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

        /* Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
        }
    </style>
</div>