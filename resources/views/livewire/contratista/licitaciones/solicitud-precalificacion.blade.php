<div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('contratista.licitaciones.show', $licitacion->id) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📋 Solicitud de Precalificación</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $licitacion->titulo }}</p>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    @if(session()->has('message'))
    <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl">
        {{ session('message') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel Principal -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Estado Actual -->
            @if($precalificacion)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estado de tu Precalificación</h2>
                
                <div class="flex items-center gap-4">
                    @switch($precalificacion->estado)
                        @case('pendiente')
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-yellow-600">Pendiente de Revisión</p>
                                <p class="text-sm text-gray-500">Tu solicitud está siendo revisada. Te notificaremos cuando haya una resolución.</p>
                            </div>
                            @break
                        @case('aprobada')
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-green-600">¡Aprobada!</p>
                                <p class="text-sm text-gray-500">Tu precalificación fue aprobada. Ya puedes postular a esta licitación.</p>
                                <a href="{{ route('contratista.licitaciones.postular', $licitacion->id) }}" class="inline-block mt-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">
                                    🚀 Postular Ahora
                                </a>
                            </div>
                            @break
                        @case('rechazada')
                            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                                <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-red-600">Rechazada</p>
                                <p class="text-sm text-gray-500">Tu precalificación fue rechazada. Puedes rectificar y enviar nuevamente.</p>
                            </div>
                            @break
                        @case('rectificando')
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-blue-600">Rectificación Enviada</p>
                                <p class="text-sm text-gray-500">Tu rectificación está siendo revisada nuevamente.</p>
                            </div>
                            @break
                    @endswitch
                </div>

                <!-- Motivo de Rechazo -->
                @if($precalificacion->estaRechazada() && $precalificacion->motivo_rechazo)
                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-2">Motivo del Rechazo:</h3>
                    <p class="text-sm text-red-700 dark:text-red-400">{{ $precalificacion->motivo_rechazo }}</p>
                </div>
                @endif

                <!-- Botón de Rectificación -->
                @if($precalificacion->estaRechazada() && !$showRectificacionForm)
                <button wire:click="mostrarFormRectificacion" class="mt-4 w-full px-4 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition">
                    🔄 Rectificar y Enviar Nuevamente
                </button>
                @endif

                <!-- Formulario de Rectificación -->
                @if($showRectificacionForm)
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                    <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-3">Rectificación</h3>
                    <textarea wire:model="comentariosRectificacion" rows="4" class="w-full px-4 py-3 border border-blue-200 dark:border-blue-700 rounded-xl dark:bg-gray-800 dark:text-white" placeholder="Explica las correcciones realizadas o información adicional..."></textarea>
                    <p class="text-xs text-gray-500 mt-1 mb-3">Describe qué has corregido o qué información adicional aportas.</p>
                    <div class="flex gap-3">
                        <button wire:click="enviarRectificacion" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            Enviar Rectificación
                        </button>
                        <button wire:click="$set('showRectificacionForm', false)" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                            Cancelar
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @else
            <!-- Formulario Nueva Solicitud -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Solicitar Precalificación</h2>
                
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    Para postular a esta licitación debes pasar primero por un proceso de precalificación. 
                    Completa la ficha técnica de tu empresa y adjunta los documentos requeridos.
                </p>

                <!-- 1. Antecedentes de la Organización -->
                <div class="mb-8 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span>🏢</span> Antecedentes de la Organización
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nro. Trabajadores</label>
                            <input wire:model="nro_trabajadores" type="number" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('nro_trabajadores') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Años Experiencia</label>
                            <input wire:model="anios_experiencia" type="number" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('anios_experiencia') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Información Financiera -->
                <div class="mb-8 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span>💰</span> Información Financiera (Último Balance)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Moneda</label>
                            <select wire:model="moneda_financiera" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                                <option value="CLP">Pesos Chilenos (CLP)</option>
                                <option value="USD">Dólares (USD)</option>
                                <option value="UF">Unidad de Fomento (UF)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Capital Social</label>
                            <input wire:model="capital_social" type="number" step="0.01" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('capital_social') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Patrimonio Neto</label>
                            <input wire:model="patrimonio_neto" type="number" step="0.01" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('patrimonio_neto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Ventas Último Año</label>
                            <input wire:model="ventas_ultimo_anio" type="number" step="0.01" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('ventas_ultimo_anio') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Seguridad y Salud (HSE) -->
                <div class="mb-8 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span>⛑️</span> Seguridad (HSE) y Calidad
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tasa Accidentabilidad (%)</label>
                            <input wire:model="tasa_accidentabilidad" type="number" step="0.01" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('tasa_accidentabilidad') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tasa Siniestralidad (%)</label>
                            <input wire:model="tasa_siniestralidad" type="number" step="0.01" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('tasa_siniestralidad') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-3 mt-4">
                        <label class="flex items-center gap-2">
                            <input wire:model="tiene_programa_prevencion" type="checkbox" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Cuenta con Programa de Prevención de Riesgos</span>
                        </label>
                        <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                        <label class="flex items-center gap-2">
                            <input wire:model="tiene_iso_9001" type="checkbox" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Certificación ISO 9001 (Calidad)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input wire:model="tiene_iso_14001" type="checkbox" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Certificación ISO 14001 (Medio Ambiente)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input wire:model="tiene_iso_45001" type="checkbox" class="rounded border-gray-300 text-violet-600 shadow-sm focus:border-violet-300 focus:ring focus:ring-violet-200 focus:ring-opacity-50">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Certificación ISO 45001 (Seguridad y Salud)</span>
                        </label>
                    </div>
                </div>

                <!-- 4. Representante Legal -->
                <div class="mb-8 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                    <h3 class="text-md font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span>⚖️</span> Representante Legal
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                            <input wire:model="nombre_representante_legal" type="text" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('nombre_representante_legal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">RUT</label>
                            <input wire:model="rut_representante_legal" type="text" class="w-full text-sm border-gray-300 rounded-lg focus:ring-violet-500 focus:border-violet-500">
                            @error('rut_representante_legal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Comentarios (opcional)</label>
                    <textarea wire:model="comentarios" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-violet-500" placeholder="Información adicional que quieras aportar..."></textarea>
                </div>

                <!-- Documentos Requeridos a Subir -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">📎 Documentos Requeridos</label>
                    <p class="text-xs text-gray-500 mb-4">Sube un archivo para cada documento requerido. Los marcados con (*) son obligatorios.</p>
                    
                    @if($requisitos->count() > 0)
                    <div class="space-y-3">
                        @foreach($requisitos as $index => $requisito)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border {{ $requisito->es_obligatorio ? 'border-red-200 dark:border-red-700' : 'border-gray-200 dark:border-gray-600' }}">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    @if($requisito->es_obligatorio)
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-bold">* Obligatorio</span>
                                    @else
                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-600 rounded text-xs font-medium">Opcional</span>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $requisito->nombre_requisito }}</span>
                                </div>
                            </div>
                            <input type="file" wire:model="documentosRequisitos.{{ $requisito->id }}" class="text-sm w-full file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                            @if(isset($documentosRequisitos[$requisito->id]))
                            <p class="mt-1 text-xs text-green-600">✓ Archivo cargado</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500 p-4 bg-gray-50 rounded-lg">No hay requisitos específicos. Puedes agregar documentos adicionales si lo deseas.</p>
                    @endif

                    <!-- Documentos Adicionales (opcional) -->
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Documentos Adicionales (opcional)</h4>
                        <div class="flex gap-2">
                            <input wire:model="nuevoDocNombre" type="text" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-800 dark:text-white text-sm" placeholder="Nombre del documento">
                            <input wire:model="nuevoDocArchivo" type="file" class="text-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png">
                        </div>
                        <button wire:click="addDocumento" type="button" class="mt-2 w-full px-3 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300">
                            + Agregar Documento Adicional
                        </button>
                    </div>

                    @if(count($documentos) > 0)
                    <div class="mt-3 space-y-2">
                        <p class="text-xs text-gray-500 font-medium">Documentos adicionales cargados:</p>
                        @foreach($documentos as $index => $doc)
                        <div class="flex items-center justify-between p-3 bg-violet-50 dark:bg-violet-900/30 rounded-lg">
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $doc['nombre'] }}</span>
                                @if($doc['archivoNombre'])
                                <span class="block text-xs text-gray-500">📎 {{ $doc['archivoNombre'] }}</span>
                                @endif
                            </div>
                            <button wire:click="removeDocumento({{ $index }})" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Botón Enviar -->
                <button wire:click="enviarSolicitud" class="w-full px-6 py-4 bg-gradient-to-r from-violet-600 to-purple-600 text-white rounded-xl font-semibold text-lg hover:from-violet-700 hover:to-purple-700 transition shadow-lg">
                    📤 Enviar Solicitud de Precalificación
                </button>
            </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Plazo de Precalificación (destacado) -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl p-6 border-2 border-amber-300 dark:border-amber-700">
                <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200 mb-3">⏰ Plazo para Precalificarse</h3>
                <div class="text-center py-4">
                    @if($licitacion->fecha_inicio_precalificacion && $licitacion->fecha_fin_precalificacion)
                    <p class="text-xs text-amber-700 dark:text-amber-300 mb-2">Puedes enviar tu solicitud entre:</p>
                    <p class="text-lg font-bold text-amber-900 dark:text-amber-100">
                        {{ \Carbon\Carbon::parse($licitacion->fecha_inicio_precalificacion)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-amber-600 dark:text-amber-400 my-1">y</p>
                    <p class="text-lg font-bold text-red-600 dark:text-red-400">
                        {{ \Carbon\Carbon::parse($licitacion->fecha_fin_precalificacion)->format('d/m/Y H:i') }}
                    </p>
                    @php
                        $now = now();
                        $finPrecal = \Carbon\Carbon::parse($licitacion->fecha_fin_precalificacion);
                        $diasRestantes = $now->diffInDays($finPrecal, false);
                    @endphp
                    @if($diasRestantes > 0)
                    <p class="mt-3 px-3 py-1 bg-amber-200 dark:bg-amber-800 rounded-full text-xs font-medium text-amber-800 dark:text-amber-200 inline-block">
                        ⏳ Quedan {{ $diasRestantes }} día(s)
                    </p>
                    @elseif($diasRestantes == 0)
                    <p class="mt-3 px-3 py-1 bg-red-200 dark:bg-red-800 rounded-full text-xs font-bold text-red-800 dark:text-red-200 inline-block">
                        ⚠️ ¡Último día!
                    </p>
                    @else
                    <p class="mt-3 px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-full text-xs font-medium text-gray-600 dark:text-gray-300 inline-block">
                        Plazo vencido
                    </p>
                    @endif
                    @else
                    <p class="text-sm text-gray-500">Fechas no definidas</p>
                    @endif
                </div>
            </div>

            <!-- Documentos Descargables de la Licitación -->
            @if($documentosLicitacion->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">📥 Documentos para Descargar</h3>
                <p class="text-xs text-gray-500 mb-3">Descarga estos documentos como referencia o formatos a completar.</p>
                <ul class="space-y-2">
                    @foreach($documentosLicitacion as $doc)
                    <li class="flex items-center gap-2 p-2 bg-violet-50 dark:bg-violet-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-violet-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        @if($doc->ruta_archivo)
                        <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank" class="text-violet-600 hover:underline text-sm font-medium flex-1">
                            {{ $doc->nombre_documento }}
                        </a>
                        <span class="text-xs text-gray-400">📎</span>
                        @else
                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $doc->nombre_documento }}</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Requisitos de Precalificación (referencia) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">📋 Requisitos de Precalificación</h3>
                @if($requisitos->count() > 0)
                <ul class="space-y-2">
                    @foreach($requisitos as $requisito)
                    <li class="flex items-start gap-2 text-sm">
                        @if($requisito->es_obligatorio)
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-medium flex-shrink-0">Oblig.</span>
                        @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium flex-shrink-0">Opc.</span>
                        @endif
                        <span class="text-gray-700 dark:text-gray-300">{{ $requisito->nombre_requisito }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-500">No hay requisitos específicos definidos.</p>
                @endif
            </div>
        </div>
    </div>
</div>
