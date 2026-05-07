<div class="max-w-7xl mx-auto px-6 py-10">
    
    <!-- Cabecera del Módulo -->
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Panel de Configuración -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-neutral-900">Parámetros</h3>
                </div>
                <button wire:click="clear"
                    class="text-[10px] font-mono text-neutral-400 hover:text-red-500 bg-neutral-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-neutral-200 hover:border-red-200 transition-all uppercase">
                    Limpiar
                </button>
            </div>

            <div class="space-y-5">
                <!-- Modelo -->
                <div>
                    <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">
                        Modelo de Cola
                    </label>
                    <select wire:model.live="model" 
                        class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-100 transition-all">
                        <option value="MM1">M/M/1 — Servidor único</option>
                        <option value="MMs">M/M/s — Múltiples servidores</option>
                        <option value="MG1">M/G/1 — Tiempos generales</option>
                        <option value="MM1K">M/M/1/K — Capacidad limitada</option>
                    </select>
                </div>

                <!-- Lambda y Mu -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">
                            Lambda <span class="text-cyan-600">(λ)</span>
                        </label>
                        <input type="number" step="0.01" wire:model="lambda"
                            class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-100 transition-all"
                            placeholder="Tasa de llegada">
                    </div>
                    <div>
                        <label class="block text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider mb-2">
                            Mu <span class="text-cyan-600">(μ)</span>
                        </label>
                        <input type="number" step="0.01" wire:model="mu"
                            class="w-full p-3 bg-neutral-50 border border-neutral-200 rounded-xl text-sm font-mono text-neutral-700 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-100 transition-all"
                            placeholder="Tasa de servicio">
                    </div>
                </div>

                <!-- Campos Dinámicos -->
                @if($model == 'MMs')
                <div>
                    <label class="block text-[10px] font-mono font-bold text-blue-600 uppercase tracking-wider mb-2">
                        Servidores <span class="text-blue-500">(s)</span>
                    </label>
                    <input type="number" wire:model="s"
                        class="w-full p-3 bg-blue-50 border border-blue-200 rounded-xl text-sm font-mono text-blue-700 focus:border-blue-400 focus:ring-1 focus:ring-blue-100 transition-all"
                        placeholder="Número de servidores">
                </div>
                @endif

                @if($model == 'MG1')
                <div>
                    <label class="block text-[10px] font-mono font-bold text-purple-600 uppercase tracking-wider mb-2">
                        Varianza <span class="text-purple-500">(σ²)</span>
                    </label>
                    <input type="number" step="0.0001" wire:model="sigma2"
                        class="w-full p-3 bg-purple-50 border border-purple-200 rounded-xl text-sm font-mono text-purple-700 focus:border-purple-400 focus:ring-1 focus:ring-purple-100 transition-all"
                        placeholder="Ej: 0.02">
                </div>
                @endif

                @if($model == 'MM1K')
                <div>
                    <label class="block text-[10px] font-mono font-bold text-amber-600 uppercase tracking-wider mb-2">
                        Capacidad <span class="text-amber-500">(K)</span>
                    </label>
                    <input type="number" wire:model="k"
                        class="w-full p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm font-mono text-amber-700 focus:border-amber-400 focus:ring-1 focus:ring-amber-100 transition-all"
                        placeholder="Capacidad del sistema">
                </div>
                @endif

                <!-- Botón Calcular -->
                <button wire:click="calculate"
                    class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 rounded-xl shadow-sm hover:shadow-md hover:shadow-cyan-100 transition-all uppercase tracking-wider text-sm mt-2">
                    <span class="flex items-center justify-center gap-2">
                        Calcular Parámetros
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7l5 5-5 5"/>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <!-- Panel de Resultados -->
        <div class="lg:col-span-2 space-y-6">
            
            @if($results)
            <!-- Tarjetas de Resultados -->
            <div class="bg-white rounded-2xl border border-neutral-200 p-6 shadow-sm">
                <h3 class="text-sm font-mono font-bold text-neutral-500 uppercase tracking-wider mb-5">
                    Resultados del Modelo <span class="text-cyan-600">{{ $model }}</span>
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    <!-- Utilización ρ -->
                    <div class="bg-cyan-50 rounded-xl p-4 border border-cyan-100">
                        <span class="block text-[10px] font-mono font-bold text-cyan-600 uppercase mb-1">Utilización</span>
                        <span class="text-lg font-bold text-cyan-800 font-mono">ρ = {{ number_format($results['rho']*100, 2) }}%</span>
                    </div>
                    
                    <!-- Probabilidad de Espera Pw -->
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                        <span class="block text-[10px] font-mono font-bold text-purple-600 uppercase mb-1">Prob. Espera</span>
                        <span class="text-lg font-bold text-purple-800 font-mono">Pw = {{ number_format($results['Pw']*100, 2) }}%</span>
                    </div>
                    
                    <!-- Ls -->
                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                        <span class="block text-[10px] font-mono font-bold text-emerald-600 uppercase mb-1">Clientes (Sist)</span>
                        <span class="text-lg font-bold text-emerald-800 font-mono">Ls = {{ number_format($results['L'], 4) }}</span>
                    </div>
                    
                    <!-- Lq -->
                    <div class="bg-emerald-100/50 rounded-xl p-4 border border-emerald-200">
                        <span class="block text-[10px] font-mono font-bold text-emerald-700 uppercase mb-1">Clientes (Cola)</span>
                        <span class="text-lg font-bold text-emerald-900 font-mono">Lq = {{ number_format($results['Lq'], 4) }}</span>
                    </div>
                    
                    <!-- Ws -->
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <span class="block text-[10px] font-mono font-bold text-blue-600 uppercase mb-1">Tiempo (Sist)</span>
                        <span class="text-lg font-bold text-blue-800 font-mono">Ws = {{ number_format($results['W'], 4) }} h</span>
                    </div>
                    
                    <!-- Wq -->
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                        <span class="block text-[10px] font-mono font-bold text-amber-600 uppercase mb-1">Tiempo (Cola)</span>
                        <span class="text-lg font-bold text-amber-800 font-mono">Wq = {{ number_format($results['Wq'], 4) }} h</span>
                    </div>
                </div>

                <!-- Desarrollo Matemático -->
                <div class="bg-neutral-50 rounded-xl p-5 border border-neutral-200">
                    <h4 class="text-xs font-mono font-bold text-neutral-600 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Desarrollo Matemático
                    </h4>
                    <div class="space-y-1.5">
                        @foreach($steps as $step) 
                        <p class="text-[11px] font-mono text-neutral-700 border-b border-neutral-100 pb-1.5 last:border-0">
                            <span class="text-neutral-400 mr-2">{{ $loop->iteration }}.</span>
                            {{ $step }}
                        </p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Historial de Cálculos con Scroll -->
            <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm overflow-hidden">
                <div class="bg-neutral-900 px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-mono font-bold text-white uppercase tracking-wider">Historial de Cálculos</span>
                    </div>
                    @if(count($history) > 0)
                    <span class="text-[10px] font-mono text-neutral-400">{{ count($history) }} registros</span>
                    @endif
                </div>
                
                @if(count($history) > 0)
                <div class="overflow-y-auto max-h-[400px] scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-neutral-100">
                    <table class="min-w-full">
                        <thead class="sticky top-0 bg-neutral-50 border-b border-neutral-200">
                            <tr>
                                <th class="px-5 py-2.5 text-left text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider">Modelo</th>
                                <th class="px-5 py-2.5 text-left text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider">Parámetros</th>
                                <th class="px-5 py-2.5 text-right text-[10px] font-mono font-bold text-neutral-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @foreach($history as $calc)
                            <tr wire:click="loadCalculation({{ $calc->id }})"
                                class="hover:bg-cyan-50 cursor-pointer transition-all group">
                                <td class="px-5 py-3.5">
                                    <span class="text-[10px] font-mono font-bold text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-lg border border-cyan-100 whitespace-nowrap">
                                        {{ $calc->model_type }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs font-mono text-neutral-600">
                                        λ: {{ $calc->inputs['lambda'] }} · μ: {{ $calc->inputs['mu'] }}
                                        @if(isset($calc->inputs['s'])) · s: {{ $calc->inputs['s'] }} @endif
                                        @if(isset($calc->inputs['k'])) · K: {{ $calc->inputs['k'] }} @endif
                                        @if(isset($calc->inputs['sigma2'])) · σ²: {{ $calc->inputs['sigma2'] }} @endif
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="text-[10px] font-mono text-neutral-400 group-hover:text-cyan-600 transition-colors inline-flex items-center gap-1">
                                        Cargar
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-12 text-center">
                    <div class="w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-mono text-neutral-400">Sin cálculos realizados aún</p>
                    <p class="text-[10px] font-mono text-neutral-300 mt-1">Configure los parámetros y presione calcular</p>
                </div>
                @endif
            </div>
            
        </div>
    </div>
</div>

<!-- Estilos para el scrollbar personalizado -->
<style>
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 0 0 8px 0;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #d4d4d4;
        border-radius: 3px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #a3a3a3;
    }
    
    /* Firefox */
    .scrollbar-thin {
        scrollbar-width: thin;
        scrollbar-color: #d4d4d4 #f5f5f5;
    }
</style>