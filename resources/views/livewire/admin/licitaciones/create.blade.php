<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.licitaciones') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Licitaciones
            </a>
            <div class="flex items-center gap-3">
                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded">ADMIN</span>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Licitación</h1>
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Crear licitación en nombre de una empresa principal</p>
        </div>

        <!-- Wizard Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @foreach([1 => 'Información', 2 => 'Fechas', 3 => 'Documentos'] as $step => $label)
                <div class="flex-1 {{ $step < 3 ? 'relative' : '' }}">
                    <div class="flex flex-col items-center w-full">
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
                    </div>
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

                <!-- Selector de Empresa Principal (Solo Admin) -->
                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-700">
                    <label class="block text-sm font-medium text-amber-800 dark:text-amber-300 mb-2">
                        🏢 Empresa Principal <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model="principal_id" 
                        class="w-full px-4 py-3 border border-amber-300 dark:border-amber-600 rounded-xl focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">-- Selecciona una empresa --</option>
                        @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->razon_social }} ({{ $empresa->rut }})</option>
                        @endforeach
                    </select>
                    @error('principal_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Título -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Título de la Licitación <span class="text-red-500">*</span>
                    </label>
                    <input 
                        wire:model="titulo" 
                        type="text" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
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
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Describe el alcance y objetivos..."
                    ></textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Tipo y Presupuesto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo <span class="text-red-500">*</span></label>
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Presupuesto</label>
                        <div class="flex gap-2">
                            <select wire:model="moneda" class="px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white">
                                <option value="CLP">CLP</option>
                                <option value="UF">UF</option>
                                <option value="USD">USD</option>
                            </select>
                            <input wire:model="presupuesto_referencial" type="number" step="0.01" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- Categorías -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categorías <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl max-h-48 overflow-y-auto">
                        @forelse($categorias as $categoria)
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-white dark:hover:bg-gray-700">
                            <input wire:model="selectedCategorias" type="checkbox" value="{{ $categoria->id }}" class="w-4 h-4 text-indigo-600 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $categoria->nombre_categoria }}</span>
                        </label>
                        @empty
                        <p class="col-span-3 text-sm text-gray-500 text-center py-4">No hay categorías.</p>
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
                        <h3 class="font-semibold text-gray-900 dark:text-white">Fechas y Configuración</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Cronograma, visita terreno y precalificación</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Consultas -->
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                        <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-4">Consultas</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-blue-800 dark:text-blue-300 mb-1">Inicio <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_inicio_consultas" type="datetime-local" class="w-full px-3 py-2 border border-blue-200 dark:border-blue-700 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm text-blue-800 dark:text-blue-300 mb-1">Cierre <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_cierre_consultas" type="datetime-local" class="w-full px-3 py-2 border border-blue-200 dark:border-blue-700 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Ofertas -->
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                        <h4 class="font-medium text-emerald-900 dark:text-emerald-300 mb-4">Ofertas</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-emerald-800 dark:text-emerald-300 mb-1">Cierre <span class="text-red-500">*</span></label>
                                <input wire:model="fecha_cierre_recepcion_ofertas" type="datetime-local" class="w-full px-3 py-2 border border-emerald-200 dark:border-emerald-700 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm text-emerald-800 dark:text-emerald-300 mb-1">Apertura</label>
                                <input wire:model="fecha_apertura_ofertas" type="datetime-local" class="w-full px-3 py-2 border border-emerald-200 dark:border-emerald-700 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adjudicación -->
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-100 dark:border-purple-800">
                    <h4 class="font-medium text-purple-900 dark:text-purple-300 mb-4">Adjudicación</h4>
                    <div class="max-w-md">
                        <input wire:model="fecha_adjudicacion_estimada" type="datetime-local" class="w-full px-3 py-2 border border-purple-200 dark:border-purple-700 rounded-lg dark:bg-gray-700 dark:text-white">
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
                    <div class="space-y-4 pt-4 border-t border-teal-200 dark:border-teal-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-teal-800 mb-1">Fecha y Hora</label>
                                <input wire:model="fecha_visita_terreno" type="datetime-local" class="w-full px-3 py-2 border border-teal-200 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex items-center mt-6">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="visita_terreno_obligatoria" class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-red-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                                    <span class="ml-2 text-sm text-gray-700">Obligatoria</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-teal-800 mb-1">Lugar</label>
                            <input wire:model="lugar_visita_terreno" type="text" class="w-full px-3 py-2 border border-teal-200 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Dirección...">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <input wire:model="contacto_visita_terreno" type="text" class="px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Contacto">
                            <input wire:model="email_contacto_visita" type="email" class="px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Email">
                            <input wire:model="telefono_contacto_visita" type="text" class="px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Teléfono">
                        </div>
                    </div>
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
                    <div class="space-y-4 pt-4 border-t border-violet-200 dark:border-violet-700">
                        
                        <!-- Selector de Formulario Predefinido -->
                        @if(count($formulariosPrecalificacion) > 0)
                        <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border-2 border-indigo-300 dark:border-indigo-600">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg">📋</span>
                                <h5 class="text-sm font-semibold text-indigo-900 dark:text-indigo-200">Usar Formulario Predefinido</h5>
                            </div>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 mb-3">
                                Selecciona un formulario de precalificación existente para cargar sus requisitos automáticamente:
                            </p>
                            <select 
                                wire:model.live="selectedFormulario" 
                                class="w-full px-4 py-3 border-2 border-indigo-300 dark:border-indigo-500 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white font-medium"
                            >
                                <option value="">-- Seleccionar formulario (opcional) --</option>
                                @foreach($formulariosPrecalificacion as $formulario)
                                <option value="{{ $formulario['id'] }}">
                                    {{ $formulario['nombre'] }} ({{ count($formulario['requisitos']) }} requisitos)
                                </option>
                                @endforeach
                            </select>
                            @if($formularioLoaded)
                            <div class="mt-3 p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                <p class="text-xs text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Requisitos del formulario cargados. Puedes ajustarlos abajo si es necesario.
                                </p>
                            </div>
                            @endif
                        </div>
                        @elseif($principal_id)
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200">
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                Esta empresa no tiene formularios de precalificación. 
                                <a href="{{ route('admin.formularios-precalificacion') }}" class="underline hover:text-amber-800">Crear formularios →</a>
                                O agrega requisitos manualmente abajo.
                            </p>
                        </div>
                        @else
                        <div class="p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Primero selecciona una Empresa Principal para ver sus formularios de precalificación disponibles.
                            </p>
                        </div>
                        @endif
                        
                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-violet-800 mb-1">Fecha Inicio</label>
                                <input wire:model="fecha_inicio_precalificacion" type="datetime-local" class="w-full px-3 py-2 border border-violet-200 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm text-violet-800 mb-1">Fecha Término</label>
                                <input wire:model="fecha_fin_precalificacion" type="datetime-local" class="w-full px-3 py-2 border border-violet-200 rounded-lg dark:bg-gray-700 dark:text-white">
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
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-violet-200">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-violet-900">🎤 Entrevista</span>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="requiere_entrevista" class="w-4 h-4 text-violet-600 bg-white border-gray-300 rounded">
                                    <span class="ml-2 text-xs text-gray-600">Incluir</span>
                                </label>
                            </div>
                            @if($requiere_entrevista)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input wire:model="fecha_entrevista" type="datetime-local" class="px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white">
                                <input wire:model="lugar_entrevista" type="text" class="px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Lugar / Modalidad">
                            </div>
                            @endif
                        </div>

                        <!-- Documentos de Precalificación -->
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-violet-200">
                            <h5 class="text-sm font-medium text-violet-900 mb-3">📄 Documentos de Precalificación</h5>
                            <div class="flex gap-2 mb-3">
                                <input wire:model="nuevoDocPrecalNombre" type="text" class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Nombre del documento">
                                <input wire:model="nuevoDocPrecalArchivo" type="file" class="text-xs" accept=".pdf,.doc,.docx,.xls,.xlsx">
                            </div>
                            <button wire:click="addDocumentoPrecal" type="button" class="w-full px-3 py-1.5 bg-violet-600 text-white rounded-lg hover:bg-violet-700 text-xs font-medium">+ Agregar Documento</button>
                            @if(count($documentosPrecalificacion) > 0)
                            <div class="space-y-2 mt-3">
                                @foreach($documentosPrecalificacion as $index => $doc)
                                <div class="flex items-center justify-between p-2 bg-violet-50 rounded-lg text-sm">
                                    <span class="text-violet-800">{{ $doc['nombre'] }}</span>
                                    <button wire:click="removeDocumentoPrecal({{ $index }})" type="button" class="text-red-500 text-xs">✕</button>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Requisitos de Precalificación -->
                        <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-violet-200">
                            <h5 class="text-sm font-medium text-violet-900 mb-3">📝 Requisitos para Precalificar</h5>
                            
                            <!-- Selector del Catálogo -->
                            <div class="mb-4 p-3 bg-violet-50 dark:bg-violet-900/30 rounded-lg">
                                <label class="block text-xs font-medium text-violet-700 dark:text-violet-300 mb-2">Seleccionar del catálogo:</label>
                                <div class="flex gap-2">
                                    <select wire:model="selectedRequisitoCatalogo" class="flex-1 px-3 py-2 text-sm border border-violet-300 dark:border-violet-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                        <option value="">-- Seleccionar requisito --</option>
                                        @foreach($catalogoRequisitos as $catalogoItem)
                                        <option value="{{ $catalogoItem->id }}">{{ $catalogoItem->nombre_requisito }} {{ $catalogoItem->criterio_cumplimiento ? '→ ' . Str::limit($catalogoItem->criterio_cumplimiento, 50) : '' }}</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="addRequisitoDesdeCatalogo" type="button" class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 text-xs font-medium">
                                        + Agregar
                                    </button>
                                </div>
                                @error('selectedRequisitoCatalogo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                                @if($catalogoRequisitos->isEmpty())
                                <p class="mt-2 text-xs text-amber-600">
                                    <a href="{{ route('admin.catalogo-requisitos') }}" class="underline hover:text-amber-700">Ir a crear requisitos en el catálogo →</a>
                                </p>
                                @endif
                            </div>

                            <!-- Agregar manualmente (alternativa) -->
                            <div class="mb-4">
                                <label class="block text-xs text-gray-500 mb-1">O agregar manualmente:</label>
                                <div class="flex gap-2">
                                    <input wire:model="nuevoRequisitoPrecal" wire:keydown.enter.prevent="addRequisitoPrecal" type="text" class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Ej: Experiencia mínima 5 años">
                                    <button wire:click="addRequisitoPrecal" type="button" class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-xs">Agregar</button>
                                </div>
                            </div>

                            <!-- Lista de requisitos agregados -->
                            @if(count($requisitosPrecalificacion) > 0)
                            <div class="space-y-2">
                                @foreach($requisitosPrecalificacion as $index => $requisito)
                                <div class="p-3 bg-violet-50 dark:bg-violet-900/20 rounded-lg border border-violet-100 dark:border-violet-700">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <button wire:click="toggleRequisitoPrecalObligatorio({{ $index }})" type="button">
                                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $requisito['obligatorio'] ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ $requisito['obligatorio'] ? 'Obligatorio' : 'Opcional' }}
                                                    </span>
                                                </button>
                                                @if(!empty($requisito['from_catalog']))
                                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-violet-200 text-violet-800">📚 Catálogo</span>
                                                @endif
                                            </div>
                                            <p class="text-sm font-medium text-violet-900 dark:text-violet-100">{{ $requisito['nombre'] }}</p>
                                            @if(!empty($requisito['criterio']))
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                <span class="font-medium">Criterio:</span> {{ $requisito['criterio'] }}
                                            </p>
                                            @endif
                                        </div>
                                        <button wire:click="removeRequisitoPrecal({{ $index }})" type="button" class="text-red-500 hover:text-red-700 text-sm ml-2">✕</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-xs text-gray-500 text-center py-3">No hay requisitos agregados aún</p>
                            @endif
                        </div>

                        <!-- Notas -->
                        <textarea wire:model="notas_precalificacion" rows="2" class="w-full px-3 py-2 border border-violet-200 rounded-lg text-sm dark:bg-gray-700 dark:text-white" placeholder="Notas adicionales sobre la precalificación..."></textarea>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- PASO 3: Documentos -->
            @if($currentStep === 3)
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="h-10 w-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Documentos y Requisitos</h3>
                    </div>
                </div>

                <!-- Documentos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Documentos</label>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                            <input wire:model="nuevoDocNombre" type="text" class="px-3 py-2 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white text-sm" placeholder="Nombre del documento *">
                            <input wire:model="nuevoDocArchivo" type="file" class="text-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.png">
                        </div>
                        <button wire:click="addDocumento" type="button" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">+ Agregar</button>
                    </div>
                    @if(count($documentosData) > 0)
                    <div class="space-y-2">
                        @foreach($documentosData as $index => $doc)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $doc['nombre'] }} @if($doc['archivoNombre']) <span class="text-xs text-gray-500">({{ $doc['archivoNombre'] }})</span>@endif</span>
                            <button wire:click="removeDocumento({{ $index }})" type="button" class="text-red-500">✕</button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Requisitos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requisitos para Contratistas</label>
                    <div class="flex gap-2 mb-4">
                        <input wire:model="nuevoRequisito" wire:keydown.enter.prevent="addRequisito" type="text" class="flex-1 px-4 py-2 border border-gray-300 rounded-xl dark:bg-gray-700 dark:text-white" placeholder="Ej: Certificado de antecedentes">
                        <button wire:click="addRequisito" type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">Agregar</button>
                    </div>
                    @if(count($requisitos) > 0)
                    <div class="space-y-2">
                        @foreach($requisitos as $index => $requisito)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <button wire:click="toggleRequisitoObligatorio({{ $index }})" type="button">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $requisito['obligatorio'] ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $requisito['obligatorio'] ? 'Obligatorio' : 'Opcional' }}
                                    </span>
                                </button>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $requisito['nombre'] }}</span>
                            </div>
                            <button wire:click="removeRequisito({{ $index }})" type="button" class="text-red-500">✕</button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex justify-between">
                <div>
                    @if($currentStep > 1)
                    <button wire:click="previousStep" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">
                        ← Anterior
                    </button>
                    @endif
                </div>
                <div class="flex gap-3">
                    @if($currentStep === $totalSteps)
                    <button wire:click="guardarBorrador" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">
                        Guardar Borrador
                    </button>
                    <button wire:click="enviarRevision" type="button" class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg">
                        ✓ Crear y Publicar
                    </button>
                    @else
                    <button wire:click="nextStep" type="button" class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg">
                        Siguiente →
                    </button>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
