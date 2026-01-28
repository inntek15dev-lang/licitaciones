<div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📋 Precalificaciones</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de solicitudes de precalificación de contratistas</p>
        </div>
        <div class="flex items-center gap-4">
            @if($pendientes > 0)
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                {{ $pendientes }} pendiente{{ $pendientes > 1 ? 's' : '' }}
            </span>
            @endif
            @if($rectificando > 0)
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                {{ $rectificando }} rectificando
            </span>
            @endif
        </div>
    </div>

    <!-- Mensajes Flash -->
    @if(session()->has('message'))
    <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">
        {{ session('message') }}
    </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6 border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Buscar</label>
                <input wire:model.live.debounce.300ms="busqueda" type="text" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="RUT o razón social...">
            </div>
            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Estado</label>
                <select wire:model.live="filtroEstado" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="rectificando">Rectificando</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Licitación</label>
                <select wire:model.live="filtroLicitacion" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Todas</option>
                    @foreach($licitaciones as $lic)
                    <option value="{{ $lic->id }}">{{ Str::limit($lic->titulo, 50) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="limpiarFiltros" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contratista</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Licitación</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Solicitud</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($precalificaciones as $precal)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $precal->contratista->razon_social ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $precal->contratista->rut ?? '' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-white">{{ Str::limit($precal->licitacion->titulo ?? 'N/A', 40) }}</div>
                        <div class="text-xs text-gray-500">{{ $precal->licitacion->codigo_licitacion ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($precal->estado)
                            @case('pendiente')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pendiente</span>
                                @break
                            @case('aprobada')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Aprobada</span>
                                @break
                            @case('rechazada')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Rechazada</span>
                                @break
                            @case('rectificando')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Rectificando</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $precal->fecha_solicitud?->format('d/m/Y H:i') ?? $precal->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.precalificaciones.revisar', $precal->id) }}" class="inline-flex items-center px-3 py-1.5 bg-violet-600 text-white rounded-lg text-sm font-medium hover:bg-violet-700 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Revisar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>No hay precalificaciones que coincidan con los filtros.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $precalificaciones->links() }}
    </div>
</div>
