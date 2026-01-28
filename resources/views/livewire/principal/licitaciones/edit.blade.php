<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navegación -->
        <div class="mb-6">
            <a href="{{ route('principal.licitaciones.show', $licitacion) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Licitación
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-white/20 text-white">
                        {{ $licitacion->codigo_licitacion }}
                    </span>
                    @if($licitacion->estado === 'observada_por_ryce')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-orange-400 text-orange-900">
                            Observada por RyCE
                        </span>
                    @endif
                </div>
                <h1 class="text-2xl font-bold text-white">Editar Licitación</h1>
            </div>
        </div>

        <!-- Observaciones de RyCE -->
        @if($observacionesRyCE)
            <div class="mb-6 bg-orange-50 dark:bg-orange-900/30 border-l-4 border-orange-500 rounded-r-xl p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-orange-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200">Observaciones de RyCE</h3>
                        <p class="mt-1 text-orange-700 dark:text-orange-300">{{ $observacionesRyCE }}</p>
                        <p class="mt-3 text-sm text-orange-600 dark:text-orange-400">
                            Por favor, corrija las observaciones indicadas y envíe nuevamente para revisión.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                @for($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <button wire:click="goToStep({{ $i }})" 
                                class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-200
                                       {{ $currentStep >= $i 
                                           ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' 
                                           : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            {{ $i }}
                        </button>
                        @if($i < $totalSteps)
                            <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                        @endif
                    </div>
                @endfor
            </div>
            <div class="flex justify-between mt-2">
                <span class="text-xs text-gray-500 dark:text-gray-400">Info Básica</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Fechas</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">Documentos</span>
            </div>
        </div>

        <!-- Forms -->
        <form wire:submit.prevent="">
            <!-- Step 1: Información Básica -->
            @if($currentStep === 1)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Información Básica</h2>
                    
                    <!-- Título -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título de la Licitación <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="titulo"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Ej: Servicio de Mantención de Equipos">
                        @error('titulo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="descripcion" rows="5"
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Describa el alcance y objetivos de la licitación..."></textarea>
                        @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Tipo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                            <select wire:model="tipo_licitacion"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="publica">Pública</option>
                                <option value="privada">Privada</option>
                            </select>
                        </div>

                        <!-- Presupuesto -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Presupuesto Referencial</label>
                            <div class="flex rounded-lg shadow-sm">
                                <select wire:model="moneda" class="rounded-l-lg border-r-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                                    <option value="CLP">CLP</option>
                                    <option value="UF">UF</option>
                                    <option value="USD">USD</option>
                                </select>
                                <input type="number" wire:model="presupuesto_referencial" step="0.01"
                                       class="flex-1 rounded-r-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>

                    <!-- Categorías -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Categorías <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($categorias as $cat)
                                <label class="flex items-center p-3 border rounded-lg cursor-pointer transition-all
                                             {{ in_array($cat->id, $selectedCategorias) 
                                                 ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' 
                                                 : 'border-gray-200 dark:border-gray-600 hover:border-indigo-300' }}">
                                    <input type="checkbox" wire:model="selectedCategorias" value="{{ $cat->id }}" class="sr-only">
                                    <span class="text-sm {{ in_array($cat->id, $selectedCategorias) ? 'text-indigo-700 dark:text-indigo-300 font-medium' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $cat->nombre_categoria }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedCategorias') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            <!-- Step 2: Fechas -->
            @if($currentStep === 2)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Cronograma</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Inicio Período Consultas <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" wire:model="fecha_inicio_consultas"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            @error('fecha_inicio_consultas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cierre Período Consultas <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" wire:model="fecha_cierre_consultas"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            @error('fecha_cierre_consultas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cierre Recepción Ofertas <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" wire:model="fecha_cierre_recepcion_ofertas"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            @error('fecha_cierre_recepcion_ofertas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha Adjudicación Estimada
                            </label>
                            <input type="datetime-local" wire:model="fecha_adjudicacion_estimada"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            @error('fecha_adjudicacion_estimada') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Visita a Terreno -->
                    <div class="mt-6 p-4 bg-teal-50 dark:bg-teal-900/20 rounded-xl border border-teal-100 dark:border-teal-800">
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
                                <div>
                                    <label class="block text-sm text-teal-800 dark:text-teal-300 mb-1">Fecha y Hora <span class="text-red-500">*</span></label>
                                    <input wire:model="fecha_visita_terreno" type="datetime-local" class="w-full px-3 py-2 border border-teal-200 dark:border-teal-700 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                                </div>
                                <div class="flex items-center">
                                    <label class="relative inline-flex items-center cursor-pointer mt-6">
                                        <input type="checkbox" wire:model="visita_terreno_obligatoria" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-500"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Asistencia Obligatoria</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-teal-800 dark:text-teal-300 mb-1">Lugar / Dirección <span class="text-red-500">*</span></label>
                                <input wire:model="lugar_visita_terreno" type="text" class="w-full px-3 py-2 border border-teal-200 dark:border-teal-700 rounded-lg focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white" placeholder="Ej: Av. Principal 123, Santiago...">
                            </div>
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
                    <div class="mt-6 p-4 bg-violet-50 dark:bg-violet-900/20 rounded-xl border border-violet-100 dark:border-violet-800">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-medium text-violet-900 dark:text-violet-300">📋 Precalificación</h4>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="requiere_precalificacion" class="w-5 h-5 text-violet-600 bg-white border-gray-300 rounded focus:ring-violet-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Requiere precalificación</span>
                            </label>
                        </div>
                        
                        @if($requiere_precalificacion)
                        <div class="space-y-4 mt-4 pt-4 border-t border-violet-200 dark:border-violet-700">
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
                            <div>
                                <label class="block text-sm text-violet-800 dark:text-violet-300 mb-1">Notas de Precalificación</label>
                                <textarea wire:model="notas_precalificacion" rows="2" class="w-full px-3 py-2 border border-violet-200 dark:border-violet-700 rounded-lg focus:ring-2 focus:ring-violet-500 dark:bg-gray-700 dark:text-white text-sm" placeholder="Instrucciones o notas adicionales..."></textarea>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-violet-700 dark:text-violet-400">No se requiere precalificación para esta licitación.</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Step 3: Documentos y Requisitos -->
            @if($currentStep === 3)
                <div class="space-y-6">
                    <!-- Documentos Existentes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Documentos</h2>
                        
                        @if(count($documentosExistentes) > 0)
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Documentos actuales</h3>
                            <div class="space-y-2 mb-6">
                                @foreach($documentosExistentes as $index => $doc)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $doc['nombre'] }}</span>
                                        </div>
                                        <button wire:click="marcarDocumentoEliminar({{ $index }})" type="button"
                                                class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Agregar nuevos documentos</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Puedes agregar documentos con nombre (y opcionalmente un archivo adjunto)</p>
                        
                        <!-- Formulario para agregar documento -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                <div>
                                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Nombre del Documento <span class="text-red-500">*</span></label>
                                    <input 
                                        wire:model="nuevoDocNombre" 
                                        type="text" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white text-sm"
                                        placeholder="Ej: Formulario de Antecedentes..."
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

                        <!-- Lista de nuevos documentos -->
                        @if(count($nuevosDocumentosData) > 0)
                        <div class="space-y-2">
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400">Por agregar:</h4>
                            @foreach($nuevosDocumentosData as $index => $doc)
                            <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg {{ $doc['archivo'] ? 'bg-emerald-100' : 'bg-amber-100' }} flex items-center justify-center">
                                        <svg class="h-4 w-4 {{ $doc['archivo'] ? 'text-emerald-600' : 'text-amber-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $doc['nombre'] }}</span>
                                        @if($doc['archivoNombre'])
                                        <p class="text-xs text-gray-500">{{ $doc['archivoNombre'] }}</p>
                                        @else
                                        <p class="text-xs text-amber-600">Sin archivo adjunto</p>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="removeNuevoDocumento({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Requisitos -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Requisitos para Contratistas</h2>
                        
                        @if(count($requisitos) > 0)
                            <div class="space-y-2 mb-6">
                                @foreach($requisitos as $index => $req)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center">
                                            <button wire:click="toggleRequisito({{ $index }})" type="button"
                                                    class="mr-3 {{ $req['obligatorio'] ? 'text-red-500' : 'text-gray-400' }}">
                                                @if($req['obligatorio'])
                                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">Oblig.</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded">Opc.</span>
                                                @endif
                                            </button>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $req['nombre'] }}</span>
                                        </div>
                                        <button wire:click="removeRequisito({{ $index }})" type="button"
                                                class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <input type="text" wire:model="nuevoRequisito" wire:keydown.enter.prevent="addRequisito"
                                   class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Nombre del requisito...">
                            <button wire:click="addRequisito" type="button"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <div>
                    @if($currentStep > 1)
                        <button wire:click="previousStep" type="button"
                                class="px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Anterior
                        </button>
                    @endif
                </div>

                <div class="flex gap-3">
                    <button wire:click="guardarBorrador" type="button"
                            class="px-6 py-3 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Guardar Borrador
                    </button>

                    @if($currentStep < $totalSteps)
                        <button wire:click="nextStep" type="button"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Siguiente
                        </button>
                    @else
                        <button wire:click="enviarParaRevision" type="button"
                                class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg">
                            Enviar para Revisión
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
