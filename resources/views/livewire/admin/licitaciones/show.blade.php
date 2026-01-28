<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.licitaciones') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver a Licitaciones
            </a>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span class="font-mono text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $licitacion->codigo_licitacion }}</span>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $licitacion->titulo }}</h1>
                </div>
                <div>
                    @php
                        $estadoClasses = [
                            'borrador' => 'bg-gray-100 text-gray-800',
                            'lista_para_publicar' => 'bg-blue-100 text-blue-800',
                            'observada_por_ryce' => 'bg-amber-100 text-amber-800',
                            'publicada' => 'bg-emerald-100 text-emerald-800',
                        ];
                        $class = $estadoClasses[$licitacion->estado] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $class }}">
                        {{ \App\Models\Licitacion::ESTADOS[$licitacion->estado] ?? $licitacion->estado }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
            <p class="text-sm font-medium text-emerald-800">{{ session('message') }}</p>
        </div>
        @endif

        <!-- Action Buttons -->
        @if($licitacion->estado === 'lista_para_publicar')
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="font-medium text-blue-900 dark:text-blue-100">Esta licitación está lista para revisión</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Revisa los datos y decide si aprobar o solicitar correcciones</p>
                </div>
                <div class="flex gap-3">
                    <button 
                        wire:click="aprobar"
                        wire:confirm="¿Confirmas APROBAR y PUBLICAR esta licitación?"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors shadow-lg shadow-emerald-500/25"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Aprobar y Publicar
                    </button>
                    <button 
                        wire:click="openObservacionModal"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-xl transition-colors"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Observar
                    </button>
                </div>
            </div>
        </div>
        @endif

        @if($licitacion->estado === 'observada_por_ryce')
        <div class="mb-6 space-y-4">
            <!-- Observaciones Banner -->
            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 rounded-r-xl">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-amber-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-amber-800 dark:text-amber-200">Licitación Observada</h3>
                        <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                            La empresa principal debe corregir las observaciones, o puedes subsanar directamente.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Historial de Observaciones -->
            @if($licitacion->observaciones && $licitacion->observaciones->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Historial de Observaciones</h3>
                <div class="space-y-4">
                    @foreach($licitacion->observaciones as $obs)
                    <div class="p-4 {{ $obs->resuelta ? 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500' : 'bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500' }} rounded-r-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $obs->revisor?->name ?? 'Revisor' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $obs->fecha_observacion?->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            @if($obs->resuelta)
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">✓ Resuelta</span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Pendiente</span>
                            @endif
                        </div>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $obs->observacion }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Buttons for Observada -->
            <div class="flex gap-3">
                <button 
                    wire:click="subsanar"
                    wire:confirm="¿Confirmas SUBSANAR esta licitación? Esto limpiará las observaciones y la dejará lista para aprobación."
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors shadow-lg"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Subsanar (Marcar Corregida)
                </button>
            </div>
        </div>
        @endif

        <!-- Info Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Descripción</h3>
                <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $licitacion->descripcion_corta }}</p>
                
                <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Categorías</h4>
                    <div class="flex flex-wrap gap-2">
                        @forelse($licitacion->categorias as $categoria)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                            {{ $categoria->nombre_categoria }}
                        </span>
                        @empty
                        <span class="text-gray-500">Sin categorías</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Side Info -->
            <div class="space-y-6">
                <!-- Empresa -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Empresa Principal</h3>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $licitacion->principal?->razon_social ?? '-' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">RUT: {{ $licitacion->principal?->rut ?? '-' }}</p>
                    
                    <!-- Creador y Fecha -->
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">{{ $licitacion->creador?->name ?? 'Sin asignar' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm mt-1">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400">Creada: {{ $licitacion->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Presupuesto -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Presupuesto</h3>
                    @if($licitacion->presupuesto_referencial)
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $licitacion->moneda_presupuesto ?? 'CLP' }} {{ number_format($licitacion->presupuesto_referencial, 0, ',', '.') }}
                    </p>
                    @else
                    <p class="text-gray-500">No especificado</p>
                    @endif
                    <p class="mt-2 text-sm text-gray-500">Tipo: {{ $licitacion->tipo_licitacion === 'publica' ? 'Pública' : 'Privada' }}</p>
                </div>
            </div>
        </div>

        <!-- Fechas -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Cronograma</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Inicio Consultas</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $licitacion->fecha_inicio_consultas ? \Carbon\Carbon::parse($licitacion->fecha_inicio_consultas)->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Cierre Consultas</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $licitacion->fecha_cierre_consultas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_consultas)->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="text-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Cierre Ofertas</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $licitacion->fecha_cierre_recepcion_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-medium">Apertura</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $licitacion->fecha_apertura_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_apertura_ofertas)->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="text-center p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                    <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">Adjudicación</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $licitacion->fecha_adjudicacion_estimada ? \Carbon\Carbon::parse($licitacion->fecha_adjudicacion_estimada)->format('d/m/Y') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Documentos y Requisitos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Documentos -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Documentos Adjuntos</h3>
                @if($licitacion->documentos->count() > 0)
                <ul class="space-y-2">
                    @foreach($licitacion->documentos as $doc)
                    <li class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $doc->nombre_documento }}</span>
                        </div>
                        <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            Descargar
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Sin documentos adjuntos</p>
                @endif
            </div>

            <!-- Requisitos de Postulación -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">📋 Requisitos para Postular</h3>
                @php
                    $requisitosPostulacion = $licitacion->requisitosDocumentos->where('es_precalificacion', false);
                @endphp
                @if($requisitosPostulacion->count() > 0)
                <ul class="space-y-2">
                    @foreach($requisitosPostulacion as $req)
                    <li class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        @if($req->es_obligatorio)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Oblig.</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Opc.</span>
                        @endif
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $req->nombre_requisito }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Sin requisitos de postulación definidos</p>
                @endif
            </div>

            <!-- Requisitos de Precalificación (si aplica) -->
            @if($licitacion->requiere_precalificacion)
            <div class="bg-violet-50 dark:bg-violet-900/20 rounded-2xl shadow-lg border border-violet-200 dark:border-violet-700 p-6">
                <h3 class="font-semibold text-violet-900 dark:text-violet-200 mb-4">🛡️ Requisitos de Precalificación</h3>
                @php
                    $requisitosPrecal = $licitacion->requisitosDocumentos->where('es_precalificacion', true);
                @endphp
                @if($requisitosPrecal->count() > 0)
                <ul class="space-y-2">
                    @foreach($requisitosPrecal as $req)
                    <li class="flex items-center gap-3 p-3 bg-white dark:bg-violet-800/30 rounded-lg">
                        @if($req->es_obligatorio)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Oblig.</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Opc.</span>
                        @endif
                        <span class="text-sm text-violet-900 dark:text-violet-100">{{ $req->nombre_requisito }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-violet-600 dark:text-violet-400 text-center py-4">Sin requisitos específicos de precalificación</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Observación -->
    @if($showObservacionModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div wire:click="closeObservacionModal" class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observar Licitación</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Indica qué correcciones debe realizar la empresa:</p>
                <textarea 
                    wire:model="observacion"
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Describe las observaciones..."
                ></textarea>
                @error('observacion')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <div class="flex justify-end gap-3 mt-4">
                    <button wire:click="closeObservacionModal" class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button wire:click="observar" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl">
                        Enviar Observación
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
