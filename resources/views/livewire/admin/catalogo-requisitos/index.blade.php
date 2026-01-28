<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Catálogo de Requisitos de Precalificación</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gestiona los requisitos predefinidos que pueden usarse en licitaciones</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Requisito
            </button>
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

        <!-- Search -->
        <div class="mb-6">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar requisitos..." 
                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white"
                >
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requisito</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Criterio de Cumplimiento</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($requisitos as $requisito)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $requisito->id }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $requisito->nombre_requisito }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($requisito->criterio_cumplimiento, 60) ?: '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button 
                                wire:click="toggleActivo({{ $requisito->id }})" 
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $requisito->activo ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                title="{{ $requisito->activo ? 'Desactivar' : 'Activar' }}"
                            >
                                <span class="sr-only">{{ $requisito->activo ? 'Desactivar' : 'Activar' }}</span>
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $requisito->activo ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $requisito->id }})" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Editar">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $requisito->id }})" wire:confirm="¿Estás seguro de eliminar este requisito?" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Eliminar">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay requisitos</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comienza creando el primer requisito de precalificación</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($requisitos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $requisitos->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div wire:click="closeModal" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Panel -->
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="save">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                            {{ $editMode ? 'Editar Requisito' : 'Nuevo Requisito' }}
                        </h3>
                    </div>

                    <div class="px-6 py-6 space-y-4">
                        <!-- Nombre Requisito -->
                        <div>
                            <label for="nombre_requisito" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre del Requisito <span class="text-red-500">*</span>
                            </label>
                            <input 
                                wire:model="nombre_requisito" 
                                type="text" 
                                id="nombre_requisito"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Ej: Certificado de Deuda TGR"
                            >
                            @error('nombre_requisito')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Criterio Cumplimiento -->
                        <div>
                            <label for="criterio_cumplimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Criterio de Cumplimiento
                            </label>
                            <textarea 
                                wire:model="criterio_cumplimiento" 
                                id="criterio_cumplimiento"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Ej: Que no tenga deudas tributarias"
                            ></textarea>
                            @error('criterio_cumplimiento')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Activo -->
                        <div class="flex items-center gap-3">
                            <input 
                                wire:model="activo" 
                                type="checkbox" 
                                id="activo"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            >
                            <label for="activo" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Requisito activo (disponible para usar en licitaciones)
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                            {{ $editMode ? 'Actualizar' : 'Crear Requisito' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
