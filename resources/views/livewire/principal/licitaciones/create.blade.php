<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('principal.licitaciones') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Licitaciones
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Licitación</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Complete los datos para crear una nueva licitación</p>
        </div>

        <!-- Wizard Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @foreach([1 => 'Información Básica', 2 => 'Fechas', 3 => 'Documentos'] as $step => $label)
                <div class="flex-1 {{ $step < 3 ? 'relative' : '' }}">
                    <button 
                        wire:click="goToStep({{ $step }})"
                        class="flex flex-col items-center w-full group"
                    >
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all
                            {{ $currentStep === $step ? 'bg-indigo-600 border-indigo-600 text-white' : '' }}
                            {{ $currentStep > $step ? 'bg-emerald-500 border-emerald-500 text-white' : '' }}
                            {{ $currentStep < $step ? 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-400' : '' }}
                        ">
                            @if($currentStep > $step)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                {{ $step }}
                            @endif
                        </div>
                        <span class="mt-2 text-xs font-medium {{ $currentStep >= $step ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}">
                            {{ $label }}
                        </span>
                    </button>
                    @if($step < 3)
                    <div class="absolute top-5 left-1/2 w-full h-0.5 {{ $currentStep > $step ? 'bg-emerald-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            
            <!-- PASO 1: Información Básica -->
            @if($currentStep === 1)
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Información Básica</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Datos principales de la licitación</p>
                    </div>
                </div>

                <!-- Título -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Título de la Licitación <span class="text-red-500">*</span>
                    </label>
                    <input 
                        wire:model="titulo" 
                        type="text" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Ej: Construcción de Bodega Industrial"
                    >
                    @error('titulo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        wire:model="descripcion" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Describe en detalle el alcance y objetivos de esta licitación..."
                    ></textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Tipo y Presupuesto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Licitación <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model="tipo_licitacion" type="radio" value="publica" class="w-4 h-4 text-indigo-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Pública</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model="tipo_licitacion" type="radio" value="privada" class="w-4 h-4 text-indigo-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Privada</span>
                            </label>
                        </div>
                        @error('tipo_licitacion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Presupuesto Referencial
                        </label>
                        <div class="flex gap-2">
                            <select wire:model="moneda" class="px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white">
                                <option value="CLP">CLP</option>
                                <option value="UF">UF</option>
                                <option value="USD">USD</option>
                            </select>
                            <input 
                                wire:model="presupuesto_referencial" 
                                type="number" 
                                step="0.01"
                                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                                placeholder="0.00"
                            >
                        </div>
                    </div>
                </div>

                <!-- Categorías -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Categorías <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl max-h-48 overflow-y-auto">
                        @forelse($categorias as $categoria)
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-white dark:hover:bg-gray-700 transition-colors">
                            <input 
                                wire:model="selectedCategorias" 
                                type="checkbox" 
                                value="{{ $categoria->id }}"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $categoria->nombre_categoria }}</span>
                        </label>
                        @empty
                        <p class="col-span-3 text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                            No hay categorías. <a href="{{ route('admin.categorias') }}" class="text-indigo-600 hover:underline">Crear categorías</a>
                        </p>
                        @endforelse
                    </div>
                    @error('selectedCategorias')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            @endif

            <!-- PASO 2: Fechas -->
            @if($currentStep === 2)
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="h-10 w-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Fechas Importantes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Define el cronograma de la licitación</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Consultas -->
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                        <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-4">Período de Consultas</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-blue-800 dark:text-blue-300 mb-1">Inicio <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_inicio_consultas" type="datetime-local" class="w-full px-3 py-2 border border-blue-200 dark:border-blue-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('fecha_inicio_consultas')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm text-blue-800 dark:text-blue-300 mb-1">Cierre <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_cierre_consultas" type="datetime-local" class="w-full px-3 py-2 border border-blue-200 dark:border-blue-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @error('fecha_cierre_consultas')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Ofertas -->
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                        <h4 class="font-medium text-emerald-900 dark:text-emerald-300 mb-4">Recepción de Ofertas</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-emerald-800 dark:text-emerald-300 mb-1">Cierre Recepción <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_cierre_recepcion_ofertas" type="datetime-local" class="w-full px-3 py-2 border border-emerald-200 dark:border-emerald-700 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                                @error('fecha_cierre_recepcion_ofertas')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm text-emerald-800 dark:text-emerald-300 mb-1">Apertura <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_apertura_ofertas" type="datetime-local" class="w-full px-3 py-2 border border-emerald-200 dark:border-emerald-700 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                                @error('fecha_apertura_ofertas')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adjudicación -->
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-100 dark:border-purple-800">
                    <h4 class="font-medium text-purple-900 dark:text-purple-300 mb-4">Adjudicación</h4>
                    <div class="max-w-md">
                        <label class="block text-sm text-purple-800 dark:text-purple-300 mb-1">Fecha Estimada</label>
                        <input wire:model="fecha_adjudicacion_estimada" type="datetime-local" class="w-full px-3 py-2 border border-purple-200 dark:border-purple-700 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                        @error('fecha_adjudicacion_estimada')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Visita a Terreno -->
                <div class="p-4 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-100 dark:border-teal-800">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-teal-900 dark:text-teal-300">🏗️ Visita a Terreno</h4>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="requiere_visita_terreno" class="w-5 h-5 text-teal-600 bg-white border-gray-300 rounded focus:ring-teal-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Requiere visita a terreno</span>
                        </label>
                    </div>
                    
                    @if($requiere_visita_terreno)
                    <div class="space-y-4 mt-4 pt-4 border-t border-teal-200 dark:border-teal-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Fecha y Hora -->
                            <div>
                                <label class="block text-sm text-teal-800 dark:text-teal-300 mb-1">Fecha y Hora <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_visita_terreno" type="datetime-local" class="w-full px-3 py-2 border border-teal-200 dark:border-teal-700 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                                @error('fecha_visita_terreno')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            
                            <!-- Obligatoria -->
                            <div class="flex items-center">
                                <label class="relative inline-flex items-center cursor-pointer mt-6">
                                    <input type="checkbox" wire:model="visita_terreno_obligatoria" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-500"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Asistencia Obligatoria</span>
                                </label>
                            </div>
                        </div>

                        <!-- Lugar -->
                        <div>
                            <label class="block text-sm text-teal-800 dark:text-teal-300 mb-1">Lugar / Dirección <span class="text-red-500">*</span></label>
                            <input wire:model="lugar_visita_terreno" type="text" class="w-full px-3 py-2 border border-teal-200 dark:border-teal-700 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" placeholder="Ej: Av. Principal 123, Santiago...">
                            @error('lugar_visita_terreno')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <!-- Contacto -->
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-teal-200 dark:border-teal-700">
                            <h5 class="text-sm font-medium text-teal-900 dark:text-teal-200 mb-3">📞 Contacto para la Visita</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Nombre</label>
                                    <input wire:model="contacto_visita_terreno" type="text" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" placeholder="Nombre del contacto">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Email</label>
                                    <input wire:model="email_contacto_visita" type="email" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" placeholder="email@empresa.cl">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Teléfono</label>
                                    <input wire:model="telefono_contacto_visita" type="text" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" placeholder="+56 9 1234 5678">
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-teal-700 dark:text-teal-400">No se requiere visita a terreno para esta licitación.</p>
                    @endif
                </div>

                <!-- Precalificación -->
                <div class="p-4 bg-violet-50 dark:bg-violet-900/20 rounded-xl border border-violet-100 dark:border-violet-800">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-violet-900 dark:text-violet-300">📋 Precalificación</h4>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="requiere_precalificacion" class="w-5 h-5 text-violet-600 bg-white border-gray-300 rounded focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Requiere precalificación</span>
                        </label>
                    </div>
                    
                    @if($requiere_precalificacion)
                    <div class="space-y-4 mt-4 pt-4 border-t border-violet-200 dark:border-violet-700">
                        <!-- Fechas de Precalificación -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-violet-800 dark:text-violet-300 mb-1">Fecha Inicio <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_inicio_precalificacion" type="datetime-local" class="w-full px-3 py-2 border border-violet-200 dark:border-violet-700 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm text-violet-800 dark:text-violet-300 mb-1">Fecha Término <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_fin_precalificacion" type="datetime-local" class="w-full px-3 py-2 border border-violet-200 dark:border-violet-700 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <!-- Responsable de Precalificación -->
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                            <h5 class="text-sm font-medium text-amber-900 dark:text-amber-200 mb-3">👥 ¿Quién puede precalificar contratistas?</h5>
                            <div class="flex flex-wrap gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="responsable_precalificacion" type="radio" value="ryce" class="w-4 h-4 text-amber-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Solo RyCE</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="responsable_precalificacion" type="radio" value="principal" class="w-4 h-4 text-amber-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Solo Principal</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="responsable_precalificacion" type="radio" value="ambos" class="w-4 h-4 text-amber-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Ambos (RyCE y Principal)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Entrevista -->
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-violet-200 dark:border-violet-700">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="text-sm font-medium text-violet-900 dark:text-violet-200">🎤 Entrevista</h5>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="requiere_entrevista" class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-violet-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                                    <span class="ml-2 text-xs text-gray-600">{{ $requiere_entrevista ? 'Sí' : 'No' }}</span>
                                </label>
                            </div>
                            @if($requiere_entrevista)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Fecha y Hora</label>
                                    <input wire:model="fecha_entrevista" type="datetime-local" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Lugar / Modalidad</label>
                                    <input wire:model="lugar_entrevista" type="text" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white" placeholder="Ej: Oficina central / Videollamada">
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Notas -->
                        <div>
                            <label class="block text-sm text-violet-800 dark:text-violet-300 mb-1">Notas de Precalificación</label>
                            <textarea wire:model="notas_precalificacion" rows="2" class="w-full px-3 py-2 border border-violet-200 dark:border-violet-700 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white text-sm" placeholder="Instrucciones o notas adicionales sobre la precalificación..."></textarea>
                        </div>

                        <!-- Documentos de Precalificación -->
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-violet-200 dark:border-violet-700">
                            <h5 class="text-sm font-medium text-violet-900 dark:text-violet-200 mb-3">📄 Documentos de Precalificación</h5>
                            <div class="flex gap-2 mb-3">
                                <input wire:model="nuevoDocPrecalNombre" type="text" class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Nombre del documento">
                                <input wire:model="nuevoDocPrecalArchivo" type="file" class="text-xs" accept=".pdf,.doc,.docx,.xls,.xlsx">
                            </div>
                            <button wire:click="addDocumentoPrecal" type="button" class="w-full px-3 py-1.5 bg-violet-600 text-white rounded-lg hover:bg-violet-700 text-xs font-medium">+ Agregar Documento</button>
                            @if(count($documentosPrecalificacion) > 0)
                            <div class="space-y-2 mt-3">
                                @foreach($documentosPrecalificacion as $index => $doc)
                                <div class="flex items-center justify-between p-2 bg-violet-50 dark:bg-violet-900/30 rounded-lg text-sm">
                                    <span class="text-violet-800 dark:text-violet-200">{{ $doc['nombre'] }}</span>
                                    <button wire:click="removeDocumentoPrecal({{ $index }})" type="button" class="text-red-500 text-xs">✕</button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Requisitos de Precalificación -->
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-violet-200 dark:border-violet-700">
                            <h5 class="text-sm font-medium text-violet-900 dark:text-violet-200 mb-3">📝 Requisitos para Precalificar</h5>
                            <div class="flex gap-2 mb-3">
                                <input wire:model="nuevoRequisitoPrecal" wire:keydown.enter.prevent="addRequisitoPrecal" type="text" class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Ej: Experiencia mínima 5 años">
                                <button wire:click="addRequisitoPrecal" type="button" class="px-3 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 text-xs">Agregar</button>
                            </div>
                            @if(count($requisitosPrecalificacion) > 0)
                            <div class="space-y-2">
                                @foreach($requisitosPrecalificacion as $index => $requisito)
                                <div class="flex items-center justify-between p-2 bg-violet-50 dark:bg-violet-900/30 rounded-lg text-sm">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="toggleRequisitoPrecalObligatorio({{ $index }})" type="button">
                                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $requisito['obligatorio'] ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $requisito['obligatorio'] ? 'Obligatorio' : 'Opcional' }}
                                            </span>
                                        </button>
                                        <span class="text-violet-800 dark:text-violet-200">{{ $requisito['nombre'] }}</span>
                                    </div>
                                    <button wire:click="removeRequisitoPrecal({{ $index }})" type="button" class="text-red-500 text-xs">✕</button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-violet-700 dark:text-violet-400">No se requiere precalificación para esta licitación.</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- PASO 3: Documentos y Requisitos -->
            @if($currentStep === 3)
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="h-10 w-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Documentos y Requisitos</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sube las bases y define los requisitos</p>
                    </div>
                </div>

                <!-- Upload Documentos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Documentos de la Licitación
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Agrega los documentos base (pueden ser solo nombres o con archivo adjunto)</p>
                    
                    <!-- Formulario para agregar documento -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <div>
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Nombre del Documento <span class="text-red-500">*</span></label>
                                <input 
                                    wire:model="nuevoDocNombre" 
                                    type="text" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white text-sm"
                                    placeholder="Ej: Formulario de Antecedentes, Muestra Técnica..."
                                >
                                @error('nuevoDocNombre')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Archivo (opcional)</label>
                                <input 
                                    wire:model="nuevoDocArchivo" 
                                    type="file" 
                                    class="w-full text-sm text-gray-600 dark:text-gray-400
                                           file:mr-3 file:py-2 file:px-3
                                           file:rounded-lg file:border-0
                                           file:text-xs file:font-medium
                                           file:bg-indigo-50 file:text-indigo-700
                                           hover:file:bg-indigo-100"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.png"
                                >
                            </div>
                        </div>
                        <button wire:click="addDocumento" type="button" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            + Agregar Documento
                        </button>
                    </div>

                    <!-- Lista de documentos agregados -->
                    @if(count($documentosData) > 0)
                    <div class="space-y-2">
                        @foreach($documentosData as $index => $doc)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg {{ $doc['archivo'] ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-amber-100 dark:bg-amber-900/30' }} flex items-center justify-center">
                                    @if($doc['archivo'])
                                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    @else
                                    <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $doc['nombre'] }}</span>
                                    @if($doc['archivoNombre'])
                                    <p class="text-xs text-gray-500 dark:text-gray-400">📎 {{ $doc['archivoNombre'] }}</p>
                                    @else
                                    <p class="text-xs text-amber-600 dark:text-amber-400">Sin archivo adjunto</p>
                                    @endif
                                </div>
                            </div>
                            <button wire:click="removeDocument({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-600">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sin documentos agregados</p>
                    </div>
                    @endif
                </div>

                <!-- Requisitos para Contratistas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Requisitos para Contratistas
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Define qué documentos deben adjuntar los contratistas</p>
                    
                    <div class="flex gap-2 mb-4">
                        <input 
                            wire:model="nuevoRequisito" 
                            wire:keydown.enter.prevent="addRequisito"
                            type="text" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Ej: Certificado de antecedentes laborales"
                        >
                        <button wire:click="addRequisito" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors">
                            Agregar
                        </button>
                    </div>

                    @if(count($requisitos) > 0)
                    <div class="space-y-2">
                        @foreach($requisitos as $index => $requisito)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <button wire:click="toggleRequisito({{ $index }})" type="button" class="p-1">
                                    @if($requisito['obligatorio'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Obligatorio
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300">
                                        Opcional
                                    </span>
                                    @endif
                                </button>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $requisito['nombre'] }}</span>
                            </div>
                            <button wire:click="removeRequisito({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sin requisitos definidos</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Footer con botones -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex justify-between">
                <div>
                    @if($currentStep > 1)
                    <button wire:click="previousStep" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>
                    @endif
                </div>

                <div class="flex gap-3">
                    @if($currentStep === $totalSteps)
                    <button wire:click="guardarBorrador" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                        Guardar Borrador
                    </button>
                    <button wire:click="enviarParaRevision" type="button" class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/25">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Enviar para Revisión
                    </button>
                    @else
                    <button wire:click="nextStep" type="button" class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/25">
                        Siguiente
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
