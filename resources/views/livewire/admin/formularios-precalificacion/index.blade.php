<div class="py-6">
    <div class="w-[90%] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Formularios de Precalificación</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Plantillas de requisitos por Empresa Principal para usar en licitaciones</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Formulario
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

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar formularios..." 
                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white"
                >
            </div>
            <select wire:model.live="filterPrincipal" class="px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:text-white">
                <option value="">Todas las empresas</option>
                @foreach($empresas as $empresa)
                <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                @endforeach
            </select>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-auto max-h-[600px]">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Formulario</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Empresa Principal</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Requisitos</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Estado</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700/50">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($formularios as $index => $formulario)
                        <tr class="transition-colors {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-indigo-50/50 dark:bg-gray-700/30' }} hover:bg-indigo-100 dark:hover:bg-gray-600/50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $formulario->nombre }}</div>
                                @if($formulario->descripcion)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($formulario->descripcion, 60) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $formulario->empresaPrincipal->razon_social }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-400">
                                    {{ $formulario->requisitos->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    wire:click="toggleActivo({{ $formulario->id }})" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $formulario->activo ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-600' }}"
                                    title="{{ $formulario->activo ? 'Desactivar' : 'Activar' }}"
                                >
                                    <span class="sr-only">{{ $formulario->activo ? 'Desactivar' : 'Activar' }}</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $formulario->activo ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $formulario->id }})" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="Editar">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="delete({{ $formulario->id }})" wire:confirm="¿Estás seguro de eliminar este formulario?" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Eliminar">
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
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay formularios</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Comienza creando el primer formulario de precalificación</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($formularios->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $formularios->links() }}
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
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form wire:submit="save">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                            {{ $editMode ? 'Editar Formulario' : 'Nuevo Formulario de Precalificación' }}
                        </h3>
                    </div>

                    <div class="px-6 py-6 space-y-4 max-h-[60vh] overflow-y-auto">
                        <!-- Empresa Principal -->
                        <div>
                            <label for="empresa_principal_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Empresa Principal <span class="text-red-500">*</span>
                            </label>
                            <select 
                                wire:model="empresa_principal_id" 
                                id="empresa_principal_id"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">-- Seleccionar empresa --</option>
                                @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                                @endforeach
                            </select>
                            @error('empresa_principal_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre del Formulario <span class="text-red-500">*</span>
                            </label>
                            <input 
                                wire:model="nombre" 
                                type="text" 
                                id="nombre"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Ej: Precalificación Estándar, Alta Exigencia..."
                            >
                            @error('nombre')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Descripción
                            </label>
                            <textarea 
                                wire:model="descripcion" 
                                id="descripcion"
                                rows="2"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Descripción opcional del formulario..."
                            ></textarea>
                        </div>

                        <!-- Requisitos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Requisitos del Formulario <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Selecciona los requisitos que incluirá este formulario:</p>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 max-h-60 overflow-y-auto space-y-2">
                                @forelse($catalogoRequisitos as $requisito)
                                <label class="flex items-start gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-gray-600/50 cursor-pointer transition-colors">
                                    <input 
                                        wire:model="selectedRequisitos" 
                                        type="checkbox" 
                                        value="{{ $requisito->id }}" 
                                        class="mt-0.5 w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500"
                                    >
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $requisito->nombre_requisito }}</span>
                                        @if($requisito->criterio_cumplimiento)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($requisito->criterio_cumplimiento, 80) }}</p>
                                        @endif
                                    </div>
                                </label>
                                @empty
                                <p class="text-sm text-gray-500 text-center py-4">
                                    No hay requisitos en el catálogo. 
                                    <a href="{{ route('admin.catalogo-requisitos') }}" class="text-indigo-600 hover:underline">Crear requisitos →</a>
                                </p>
                                @endforelse
                            </div>
                            @error('selectedRequisitos')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Activo -->
                        <div class="flex items-center gap-3 pt-2">
                            <input 
                                wire:model="activo" 
                                type="checkbox" 
                                id="activo"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            >
                            <label for="activo" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Formulario activo (disponible para usar en licitaciones)
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-colors shadow-lg shadow-indigo-500/25">
                            {{ $editMode ? 'Actualizar' : 'Crear Formulario' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
