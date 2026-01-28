<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                Licitaciones Disponibles
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Encuentra y postula a licitaciones públicas activas
            </p>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Búsqueda -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Buscar por título, código o descripción..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Categoría -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoría</label>
                    <select wire:model.live="categoria" 
                            class="w-full py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre_categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ordenar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ordenar por</label>
                    <select wire:model.live="ordenar" 
                            class="w-full py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="recientes">Más recientes</option>
                        <option value="cierre_proximo">Cierre próximo</option>
                        <option value="presupuesto_mayor">Mayor presupuesto</option>
                        <option value="presupuesto_menor">Menor presupuesto</option>
                    </select>
                </div>
            </div>

            @if($search || $categoria)
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-sm text-gray-500">Filtros activos:</span>
                    @if($search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            "{{ $search }}"
                            <button wire:click="$set('search', '')" class="ml-2 hover:text-blue-600">×</button>
                        </span>
                    @endif
                    @if($categoria)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                            {{ $categorias->find($categoria)->nombre_categoria ?? '' }}
                            <button wire:click="$set('categoria', '')" class="ml-2 hover:text-green-600">×</button>
                        </span>
                    @endif
                    <button wire:click="limpiarFiltros" class="text-sm text-red-600 hover:text-red-800 ml-2">
                        Limpiar todos
                    </button>
                </div>
            @endif
        </div>

        <!-- Listado de Licitaciones -->
        @if($licitaciones->count() > 0)
            <div class="grid gap-6">
                @foreach($licitaciones as $licitacion)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <!-- Info Principal -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $licitacion->codigo_licitacion }}
                                        </span>
                                        @if($licitacion->dias_restantes <= 3 && $licitacion->dias_restantes >= 0)
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                                ⏰ ¡Cierra pronto!
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $licitacion->titulo }}
                                    </h3>
                                    
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                        {{ $licitacion->descripcion_corta }}
                                    </p>

                                    <!-- Categorías -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($licitacion->categorias as $cat)
                                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $cat->nombre_categoria }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- Empresa -->
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $licitacion->principal->razon_social ?? 'N/A' }}
                                    </div>
                                </div>

                                <!-- Info Lateral -->
                                <div class="flex flex-col items-end gap-3 min-w-[200px]">
                                    <!-- Presupuesto -->
                                    @if($licitacion->presupuesto_referencial)
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Presupuesto referencial</span>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                {{ $licitacion->moneda_presupuesto ?? 'CLP' }} {{ number_format($licitacion->presupuesto_referencial, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Fecha Cierre -->
                                    <div class="text-right">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Cierre de ofertas</span>
                                        <p class="text-lg font-semibold {{ $licitacion->dias_restantes <= 3 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                            {{ \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y H:i') }}
                                        </p>
                                        <span class="text-sm {{ $licitacion->dias_restantes <= 3 ? 'text-red-500' : 'text-gray-500' }}">
                                            @if($licitacion->dias_restantes == 0)
                                                ¡Hoy es el último día!
                                            @elseif($licitacion->dias_restantes == 1)
                                                Queda 1 día
                                            @else
                                                Quedan {{ $licitacion->dias_restantes }} días
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Botón Ver Detalle -->
                                    <a href="{{ route('contratista.licitaciones.show', $licitacion) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        Ver Detalle
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $licitaciones->links() }}
            </div>
        @else
            <!-- Estado Vacío -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                    No hay licitaciones disponibles
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    @if($search || $categoria)
                        No se encontraron licitaciones con los filtros seleccionados.
                        <button wire:click="limpiarFiltros" class="text-blue-600 hover:underline ml-1">
                            Limpiar filtros
                        </button>
                    @else
                        Actualmente no hay licitaciones públicas activas. Vuelve pronto.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
