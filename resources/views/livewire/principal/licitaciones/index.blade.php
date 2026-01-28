<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mis Licitaciones</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gestiona todas tus licitaciones</p>
            </div>
            <a href="{{ route('principal.licitaciones.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $statsConfig = [
                    'borrador' => ['color' => 'gray', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    'lista_para_publicar' => ['color' => 'blue', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'publicada' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'adjudicada' => ['color' => 'green', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                ];
            @endphp
            @foreach(['borrador', 'lista_para_publicar', 'publicada', 'adjudicada'] as $estado)
            <button 
                wire:click="$set('filterEstado', '{{ $filterEstado === $estado ? '' : $estado }}')"
                class="relative p-4 bg-white dark:bg-gray-800 rounded-xl border-2 transition-all {{ $filterEstado === $estado ? 'border-indigo-500 shadow-lg shadow-indigo-500/20' : 'border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600' }}"
            >
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $estadosCounts[$estado] ?? 0 }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $estados[$estado] }}</p>
                @if($filterEstado === $estado)
                <div class="absolute top-2 right-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                        Filtrado
                    </span>
                </div>
                @endif
            </button>
            @endforeach
        </div>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar por título o código..." 
                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white"
                >
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Licitación</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ofertas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cierre</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($licitaciones as $licitacion)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                    {{ $licitacion->codigo_licitacion }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($licitacion->titulo, 50) }}</div>
                                    <div class="flex gap-1 mt-1">
                                        @foreach($licitacion->categorias->take(2) as $cat)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            {{ $cat->nombre_categoria }}
                                        </span>
                                        @endforeach
                                        @if($licitacion->categorias->count() > 2)
                                        <span class="text-xs text-gray-500">+{{ $licitacion->categorias->count() - 2 }}</span>
                                        @endif
                                    </div>
                                </div>
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
                                        'desierta' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'cancelada' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                    $class = $estadoClasses[$licitacion->estado] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                    {{ $estados[$licitacion->estado] ?? $licitacion->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    {{ $licitacion->ofertas_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $licitacion->fecha_cierre_recepcion_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y') : '-' }}
                                </div>
                                @if($licitacion->fecha_cierre_recepcion_ofertas && $licitacion->estado === 'publicada')
                                @php
                                    $diasRestantes = now()->diffInDays(\Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas), false);
                                @endphp
                                <div class="text-xs {{ $diasRestantes < 0 ? 'text-red-500' : ($diasRestantes <= 3 ? 'text-amber-500' : 'text-gray-400') }}">
                                    {{ $diasRestantes < 0 ? 'Cerrado' : ($diasRestantes == 0 ? 'Cierra hoy' : $diasRestantes . ' días restantes') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('principal.licitaciones.show', $licitacion) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Ver">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @if($licitacion->estado === 'borrador' || $licitacion->estado === 'observada_por_ryce')
                                    <a href="{{ route('principal.licitaciones.edit', $licitacion) }}" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @endif
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
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comienza creando tu primera licitación</p>
                                <a href="{{ route('principal.licitaciones.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nueva Licitación
                                </a>
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
