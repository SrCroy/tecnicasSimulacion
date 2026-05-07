<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-10">
        
        <!-- Cabecera -->
        <div class="mb-12 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-mono text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">● sistema operativo</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-neutral-900">
                    Técnicas de <span class="text-blue-600">Simulación</span>
                </h1>
                <p class="text-sm font-mono text-neutral-500 mt-2">
                    FMO UES — Ingeniería de Sistemas — Generación y validación de números aleatorios
                </p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white rounded-xl border border-neutral-200 px-4 py-3 shadow-sm">
                    <p class="text-[11px] font-mono text-neutral-400 uppercase">módulos</p>
                    <p class="text-2xl font-bold text-neutral-900">04</p>
                </div>
                <div class="bg-white rounded-xl border border-neutral-200 px-4 py-3 shadow-sm">
                    <p class="text-[11px] font-mono text-neutral-400 uppercase">pruebas</p>
                    <p class="text-2xl font-bold text-neutral-900">06</p>
                </div>
                <div class="bg-white rounded-xl border border-neutral-200 px-4 py-3 shadow-sm">
                    <p class="text-[11px] font-mono text-neutral-400 uppercase">precisión</p>
                    <p class="text-2xl font-bold text-neutral-900">α=0.05</p>
                </div>
            </div>
        </div>

        <!-- Grid de Módulos -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Card 1: Congruenciales -->
            <a href="{{ route('congruenciales') }}" class="group block bg-white rounded-2xl border border-neutral-200 p-6 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-50 transition-all duration-200">
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-neutral-900">Generadores Congruenciales</h3>
                            <p class="text-xs font-mono text-neutral-400">módulo / 01</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">
                        Xₙ₊₁
                    </span>
                </div>
                
                <p class="text-sm text-neutral-500 leading-relaxed mb-4">
                    Secuencias modulares basadas en recurrencia lineal: 
                    <code class="bg-neutral-100 px-1.5 py-0.5 rounded text-xs font-mono text-neutral-700">Xₙ₊₁ = (a·Xₙ + c) mod m</code>
                </p>
                
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">LINEAL</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">MULTIPLICATIVO</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">CUADRÁTICO</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 group-hover:gap-3 transition-all">
                    <span>ABRIR MÓDULO</span>
                    <span>→</span>
                </div>
            </a>

            <!-- Card 2: Colas -->
            <a href="{{ route('calculadora') }}" class="group block bg-white rounded-2xl border border-neutral-200 p-6 hover:border-cyan-400 hover:shadow-lg hover:shadow-cyan-50 transition-all duration-200">
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan-50 rounded-xl flex items-center justify-center text-cyan-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-neutral-900">Teoría de Colas</h3>
                            <p class="text-xs font-mono text-neutral-400">módulo / 02</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono font-bold text-cyan-600 bg-cyan-50 px-3 py-1 rounded-lg">
                        M/M/s
                    </span>
                </div>
                
                <p class="text-sm text-neutral-500 leading-relaxed mb-4">
                    Modelos de líneas de espera con parámetros:
                    <code class="bg-neutral-100 px-1.5 py-0.5 rounded text-xs font-mono text-neutral-700">Lq, Wq, Ls, Ws</code>
                </p>
                
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">M/M/1</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">M/M/s</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">M/G/1</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm font-semibold text-cyan-600 group-hover:gap-3 transition-all">
                    <span>CALCULAR</span>
                    <span>→</span>
                </div>
            </a>

            <!-- Card 3: Pseudoaleatorios -->
            <a href="{{ route('generador') }}" class="group block bg-white rounded-2xl border border-neutral-200 p-6 hover:border-violet-400 hover:shadow-lg hover:shadow-violet-50 transition-all duration-200">
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-neutral-900">Pseudoaleatorios</h3>
                            <p class="text-xs font-mono text-neutral-400">módulo / 03</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono font-bold text-violet-600 bg-violet-50 px-3 py-1 rounded-lg">
                        Ri ∈ [0,1)
                    </span>
                </div>
                
                <p class="text-sm text-neutral-500 leading-relaxed mb-4">
                    Métodos de dígitos centrales. Semillas con 
                    <code class="bg-neutral-100 px-1.5 py-0.5 rounded text-xs font-mono text-neutral-700">D ≥ 3</code>
                </p>
                
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">CUADRADOS MEDIOS</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">PRODUCTOS MEDIOS</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm font-semibold text-violet-600 group-hover:gap-3 transition-all">
                    <span>GENERAR</span>
                    <span>→</span>
                </div>
            </a>

            <!-- Card 4: Pruebas -->
            <a href="#" class="group block bg-white rounded-2xl border border-neutral-200 p-6 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-50 transition-all duration-200">
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-neutral-900">Analizador Estadístico</h3>
                            <p class="text-xs font-mono text-neutral-400">módulo / 04</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-lg">
                        H₀: μ=½
                    </span>
                </div>
                
                <p class="text-sm text-neutral-500 leading-relaxed mb-4">
                    Pruebas de independencia y uniformidad. Estadísticos:
                    <code class="bg-neutral-100 px-1.5 py-0.5 rounded text-xs font-mono text-neutral-700">χ², K-S, póker</code>
                </p>
                
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">χ²</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">K-S</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">PÓKER</span>
                    <span class="text-[10px] font-mono text-neutral-500 bg-neutral-100 px-2.5 py-1 rounded-md">CORRIDAS</span>
                </div>
                
                <div class="flex items-center gap-2 text-sm font-semibold text-amber-600 group-hover:gap-3 transition-all">
                    <span>ANALIZAR</span>
                    <span>→</span>
                </div>
            </a>

        </div>

        <!-- Footer -->
        <div class="mt-12 pt-8 border-t border-neutral-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-400 font-mono">
            <p>Técnicas de Simulación — FMO UES — Ingeniería de Sistemas</p>
            <p>const estado = <span class="text-emerald-600 font-semibold">'operativo'</span>;</p>
        </div>

    </div>
</x-app-layout>