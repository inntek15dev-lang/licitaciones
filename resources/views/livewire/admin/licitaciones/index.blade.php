<div class="py-6">
    <div class="w-[90%] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Licitaciones</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Revisa, aprueba y gestiona todas las licitaciones del sistema</p>
            </div>
            <a href="{{ route('admin.licitaciones.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-500/25 transition-all">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Licitación
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('message') }}</p>
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            @php
                $statsConfig = [
                    'lista_para_publicar' => ['label' => 'Pendientes', 'color' => 'blue', 'priority' => true],
                    'observada_por_ryce' => ['label' => 'Observadas', 'color' => 'amber', 'priority' => false],
                    'publicada' => ['label' => 'Publicadas', 'color' => 'emerald', 'priority' => false],
                    'cerrada' => ['label' => 'Cerradas', 'color' => 'purple', 'priority' => false],
                    'adjudicada' => ['label' => 'Adjudicadas', 'color' => 'green', 'priority' => false],
                ];
            @endphp
            @foreach($statsConfig as $estado => $config)
            <button 
                wire:click="$set('filterEstado', '{{ $filterEstado === $estado ? '' : $estado }}')"
                class="relative p-4 rounded-xl border-2 transition-all {{ $filterEstado === $estado ? 'border-indigo-500 shadow-lg shadow-indigo-500/20 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-gray-200' }} {{ $config['priority'] && ($estadosCounts[$estado] ?? 0) > 0 ? 'ring-2 ring-blue-400 ring-offset-2' : '' }}"
            >
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $estadosCounts[$estado] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $config['label'] }}</p>
                @if($config['priority'] && ($estadosCounts[$estado] ?? 0) > 0)
                <span class="absolute -top-2 -right-2 flex h-5 w-5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-5 w-5 bg-blue-500 items-center justify-center text-xs text-white font-bold">!</span>
                </span>
                @endif
            </button>
            @endforeach
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar por código o título..." 
                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white"
                >
            </div>
            <select wire:model.live="filterEstado" class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                <option value="">Todos los estados</option>
                @foreach($estados as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterPrincipal" class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                <option value="">Todas las empresas</option>
                @foreach($principales as $principal)
                <option value="{{ $principal->id }}">{{ $principal->razon_social }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterPrecalificacion" class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-violet-500 dark:text-white">
                <option value="">Precalificación: Todas</option>
                <option value="si">🛡️ Con Precalificación</option>
                <option value="no">Sin Precalificación</option>
            </select>
        </div>

        <!-- Table Container con scroll y altura fija -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-auto max-h-[600px]" style="min-width: 100%;">
                <table class="w-full min-w-[1200px]">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Licitación</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Empresa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Estado</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Precalif.</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Ofertas</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Fechas</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($licitaciones as $index => $licitacion)
                        <tr class="transition-colors {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-indigo-50/50 dark:bg-gray-700/30' }} hover:bg-indigo-100 dark:hover:bg-gray-600/50 {{ $licitacion->estado === 'lista_para_publicar' ? 'ring-2 ring-inset ring-blue-300' : '' }}">
                            <td class="px-4 py-3">
                                <div>
                                    <span class="font-mono text-xs font-medium text-indigo-600 dark:text-indigo-400">{{ $licitacion->codigo_licitacion }}</span>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ Str::limit($licitacion->titulo, 45) }}</div>
                                    <div class="flex gap-1 mt-1">
                                        @foreach($licitacion->categorias->take(2) as $cat)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            {{ $cat->nombre_categoria }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $licitacion->principal?->razon_social ?? '-' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $licitacion->creador?->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estadoClasses = [
                                        'borrador' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'lista_para_publicar' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'observada_por_ryce' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'publicada' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'cerrada' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                        'adjudicada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                    $class = $estadoClasses[$licitacion->estado] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                    {{ $estados[$licitacion->estado] ?? $licitacion->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($licitacion->requiere_precalificacion)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-400">
                                    Sí
                                </span>
                                @else
                                <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    {{ $licitacion->ofertas_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <div>Cierre: {{ $licitacion->fecha_cierre_recepcion_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y') : '-' }}</div>
                                    <div>Creada: {{ $licitacion->created_at->format('d/m/Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if($licitacion->estado === 'lista_para_publicar')
                                    <!-- Botón Aprobar -->
                                    <button 
                                        wire:click="aprobarLicitacion({{ $licitacion->id }})"
                                        wire:confirm="¿Confirmas aprobar y publicar esta licitación?"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors"
                                        title="Aprobar y Publicar"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Aprobar
                                    </button>
                                    <!-- Botón Observar -->
                                    <button 
                                        wire:click="observarLicitacion({{ $licitacion->id }})"
                                        wire:confirm="¿Deseas marcar esta licitación como observada?"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors"
                                        title="Observar"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        Observar
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.licitaciones.show', $licitacion->id) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Ver Detalle">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay licitaciones</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No se encontraron licitaciones con los filtros aplicados</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($licitaciones->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $licitaciones->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
