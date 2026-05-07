<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnicas de Simulación - FMO UES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'mono': ['JetBrains Mono', 'Fira Code', 'monospace'],
                    }
                }
            }
        }
    </script>
    @livewireStyles
</head>
<body class="bg-neutral-50 text-neutral-800 antialiased">

    <!-- Navbar con Autenticación Breeze -->
    <nav class="bg-white border-b border-neutral-200 sticky top-0 z-50 backdrop-blur-sm bg-white/90">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                
                <!-- Logo y Nombre del Proyecto -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-neutral-900 rounded-lg flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-sm tracking-tight">SM</span>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-base font-semibold text-neutral-900 leading-tight">Técnicas de Simulación</h1>
                        <p class="text-[11px] text-neutral-400 font-mono">FMO UES</p>
                    </div>
                    <div class="sm:hidden">
                        <h1 class="text-sm font-semibold text-neutral-900">Simulación FMO</h1>
                    </div>
                </div>

                <!-- Zona Derecha: Usuario y Menú -->
                @auth
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-1 text-sm">
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100 rounded-lg transition-colors font-medium">Inicio</a>
                    </div>

                    <!-- Dropdown de Usuario -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl border border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50 transition-all">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-neutral-700 hidden sm:block max-w-[120px] truncate">
                                {{ Auth::user()->name }}
                            </span>
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Menú Desplegable -->
                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-xl border border-neutral-200 shadow-lg shadow-neutral-200/50 p-2 z-50">
                            
                            <div class="px-3 py-3 border-b border-neutral-100 mb-1">
                                <p class="text-sm font-semibold text-neutral-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-neutral-400 font-mono truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="space-y-0.5">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900 rounded-lg transition-all group">
                                    <svg class="w-4 h-4 text-neutral-400 group-hover:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Editar Perfil</span>
                                </a>

                                <a href="{{ route('profile.edit') }}#password" class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900 rounded-lg transition-all group">
                                    <svg class="w-4 h-4 text-neutral-400 group-hover:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    <span>Cambiar Contraseña</span>
                                </a>

                                <div class="border-t border-neutral-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-all group">
                                        <svg class="w-4 h-4 text-red-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors px-4 py-2 rounded-lg hover:bg-neutral-100">
                        Iniciar Sesión
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-neutral-900 hover:bg-neutral-800 transition-colors px-4 py-2 rounded-lg">
                        Registrarse
                    </a>
                    @endif
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenido Dinámico -->
    <main>
        {{ $slot }}
    </main>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireScripts
</body>
</html>