<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mis Postulaciones</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Historial de ofertas enviadas a licitaciones</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-medium text-emerald-800 dark:text-emerald-200">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Búsqueda -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="busqueda"
                               placeholder="Buscar por título o código de licitación..."
                               class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Filtro Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                    <select wire:model.live="filtroEstado"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los estados</option>
                        @foreach($estados as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($busqueda || $filtroEstado)
                <div class="mt-4 flex justify-end">
                    <button wire:click="limpiarFiltros" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                        Limpiar filtros
                    </button>
                </div>
            @endif
        </div>

        <!-- Listado de Ofertas -->
        @if($ofertas->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Licitación</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monto Oferta</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha Envío</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($ofertas as $oferta)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-mono text-indigo-600 dark:text-indigo-400 mb-1">
                                                {{ $oferta->licitacion->codigo_licitacion ?? 'N/A' }}
                                            </span>
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ Str::limit($oferta->licitacion->titulo ?? 'Sin título', 40) }}
                                            </span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $oferta->licitacion->principal->razon_social ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                                            {{ $oferta->moneda_oferta ?? 'CLP' }} {{ number_format($oferta->monto_oferta_economica ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $estadoColors = [
                                                'pendiente_precalificacion_ryce' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                'precalificada_por_ryce' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                'no_precalificada_ryce' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                                'en_evaluacion_principal' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
                                                'adjudicada' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                                'no_adjudicada' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                'retirada' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                            ];
                                            $colorClass = $estadoColors[$oferta->estado_oferta] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                            {{ \App\Models\Oferta::ESTADOS[$oferta->estado_oferta] ?? $oferta->estado_oferta }}
                                        </span>
                                        
                                        @if($oferta->estado_oferta === 'adjudicada')
                                            <div class="mt-1">
                                                <span class="inline-flex items-center text-xs text-emerald-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                    </svg>
                                                    ¡Ganaste!
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $oferta->fecha_presentacion ? \Carbon\Carbon::parse($oferta->fecha_presentacion)->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('contratista.licitaciones.show', $oferta->licitacion_id) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                                            Ver licitación
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($ofertas->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $ofertas->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                <div class="w-20 h-20 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No tienes postulaciones</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Aún no has enviado ofertas a ninguna licitación.</p>
                <a href="{{ route('contratista.licitaciones') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Buscar Licitaciones
                </a>
            </div>
        @endif
    </div>
</div>
