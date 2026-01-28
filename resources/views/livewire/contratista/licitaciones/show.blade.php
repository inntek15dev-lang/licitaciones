<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navegación -->
        <div class="mb-6">
            <a href="{{ route('contratista.licitaciones') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Licitaciones
            </a>
        </div>

        <!-- Header con Info Principal -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-white/20 text-white">
                                {{ $licitacion->codigo_licitacion }}
                            </span>
                            <span class="px-4 py-1.5 text-sm font-semibold rounded-full 
                                @switch($licitacion->estado)
                                    @case('publicada') bg-green-400 text-green-900 @break
                                    @case('cerrada_ofertas') bg-yellow-400 text-yellow-900 @break
                                    @case('adjudicada') bg-purple-400 text-purple-900 @break
                                    @default bg-gray-300 text-gray-800
                                @endswitch">
                                {{ \App\Models\Licitacion::ESTADOS[$licitacion->estado] ?? $licitacion->estado }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            {{ $licitacion->titulo }}
                        </h1>
                        <p class="text-blue-100 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $licitacion->principal->razon_social ?? 'N/A' }}
                        </p>
                    </div>

                    @if($licitacion->presupuesto_referencial)
                        <div class="text-right bg-white/10 rounded-lg px-6 py-4">
                            <span class="text-blue-100 text-sm">Presupuesto Referencial</span>
                            <p class="text-3xl font-bold text-white">
                                {{ $licitacion->moneda_presupuesto ?? 'CLP' }} {{ number_format($licitacion->presupuesto_referencial, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Fechas Importantes -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-gray-50 dark:bg-gray-700/50">
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Consultas hasta</span>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $licitacion->fecha_cierre_consultas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_consultas)->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Cierre Ofertas</span>
                    <p class="font-semibold text-red-600 dark:text-red-400">
                        {{ $licitacion->fecha_cierre_recepcion_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y H:i') : 'N/A' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Adjudicación Est.</span>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $licitacion->fecha_adjudicacion_estimada ? \Carbon\Carbon::parse($licitacion->fecha_adjudicacion_estimada)->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Tipo</span>
                    <p class="font-semibold text-gray-900 dark:text-white capitalize">
                        {{ $licitacion->tipo_licitacion }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Descripción -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descripción
                    </h2>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! nl2br(e($licitacion->descripcion_corta)) !!}
                        @if($licitacion->descripcion_larga)
                            <hr class="my-4">
                            {!! nl2br(e($licitacion->descripcion_larga)) !!}
                        @endif
                    </div>

                    <!-- Categorías -->
                    @if($licitacion->categorias->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">CATEGORÍAS</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($licitacion->categorias as $cat)
                                    <span class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $cat->nombre_categoria }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Documentos -->
                @if($licitacion->documentos->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Documentos de la Licitación
                        </h2>
                        <div class="space-y-3">
                            @foreach($licitacion->documentos as $doc)
                                <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank"
                                   class="flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white truncate">{{ $doc->nombre_documento }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $doc->tipo_documento) }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Preguntas y Respuestas -->
                @if($licitacion->consultasRespuestas->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Preguntas y Respuestas
                        </h2>
                        <div class="space-y-4">
                            @foreach($licitacion->consultasRespuestas as $qa)
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        <span class="text-blue-600">P:</span> {{ $qa->pregunta }}
                                    </p>
                                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                                        <span class="text-green-600 font-medium">R:</span> {{ $qa->respuesta }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $qa->fecha_respuesta ? \Carbon\Carbon::parse($qa->fecha_respuesta)->format('d/m/Y H:i') : '' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Columna Lateral -->
            <div class="space-y-6">
                <!-- Acción Principal -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    @if($yaPostulo)
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Ya has postulado</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                Tu oferta está en estado: 
                                <span class="font-semibold capitalize">{{ str_replace('_', ' ', $miOferta->estado ?? 'enviada') }}</span>
                            </p>
                            <a href="{{ route('contratista.mis-ofertas') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                Ver mis ofertas
                            </a>
                        </div>
                    @elseif($requierePrecalificacion && $estadoPrecalificacion !== 'aprobada')
                        {{-- Requiere precalificación y NO está aprobada --}}
                        <div class="text-center">
                            @if(!$precalificacion)
                                {{-- No ha solicitado precalificación --}}
                                <div class="w-16 h-16 mx-auto bg-violet-100 dark:bg-violet-900 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">⚠️ Requiere Precalificación</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">
                                    Para postular a esta licitación debes precalificarte primero.
                                </p>
                                <a href="{{ route('contratista.licitaciones.precalificar', $licitacion) }}" 
                                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-semibold rounded-lg hover:from-violet-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Solicitar Precalificación
                                </a>
                            @elseif($estadoPrecalificacion === 'pendiente')
                                {{-- Precalificación pendiente --}}
                                <div class="w-16 h-16 mx-auto bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-yellow-600 mb-2">Precalificación Pendiente</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">
                                    Tu solicitud de precalificación está siendo revisada.
                                </p>
                                <a href="{{ route('contratista.licitaciones.precalificar', $licitacion) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200">
                                    Ver Estado
                                </a>
                            @elseif($estadoPrecalificacion === 'rechazada')
                                {{-- Precalificación rechazada --}}
                                <div class="w-16 h-16 mx-auto bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-red-600 mb-2">Precalificación Rechazada</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">
                                    Tu precalificación fue rechazada. Puedes rectificar y enviar nuevamente.
                                </p>
                                <a href="{{ route('contratista.licitaciones.precalificar', $licitacion) }}" 
                                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    🔄 Rectificar y Reintentar
                                </a>
                            @elseif($estadoPrecalificacion === 'rectificando')
                                {{-- Rectificación en revisión --}}
                                <div class="w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-blue-600 mb-2">Rectificación en Revisión</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">
                                    Tu rectificación está siendo revisada nuevamente.
                                </p>
                                <a href="{{ route('contratista.licitaciones.precalificar', $licitacion) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200">
                                    Ver Estado
                                </a>
                            @endif
                        </div>
                    @elseif($puedePostular)
                        {{-- Puede postular (no requiere precalificación o ya está aprobada) --}}
                        <div class="text-center">
                            @if($requierePrecalificacion && $estadoPrecalificacion === 'aprobada')
                            <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/30 rounded-lg border border-green-200 dark:border-green-700">
                                <span class="text-green-700 dark:text-green-300 text-sm font-medium">✅ Precalificación Aprobada</span>
                            </div>
                            @endif
                            <div class="w-16 h-16 mx-auto bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">¿Interesado?</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                Postula tu oferta antes del cierre
                            </p>
                            <a href="{{ route('contratista.licitaciones.postular', $licitacion) }}" 
                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Postular Oferta
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Postulación cerrada</h3>
                            <p class="text-gray-500 dark:text-gray-400">
                                El periodo de recepción de ofertas ha finalizado
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Requisitos Documentales -->
                @if($licitacion->requisitosDocumentos->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Requisitos para Postular
                        </h3>
                        <ul class="space-y-2">
                            @foreach($licitacion->requisitosDocumentos->sortBy('orden') as $req)
                                <li class="flex items-start">
                                    @if($req->es_obligatorio)
                                        <span class="flex-shrink-0 w-5 h-5 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                            <span class="text-red-600 dark:text-red-400 text-xs font-bold">*</span>
                                        </span>
                                    @else
                                        <span class="flex-shrink-0 w-5 h-5 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                            <span class="text-gray-400 text-xs">○</span>
                                        </span>
                                    @endif
                                    <div>
                                        <span class="text-gray-900 dark:text-white">{{ $req->nombre_requisito }}</span>
                                        @if($req->es_obligatorio)
                                            <span class="text-xs text-red-500 ml-1">(Obligatorio)</span>
                                        @else
                                            <span class="text-xs text-gray-400 ml-1">(Opcional)</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Información de Contacto -->
                @if($licitacion->lugar_ejecucion_trabajos || $licitacion->requiere_visita_terreno)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Información Adicional</h3>
                        
                        @if($licitacion->lugar_ejecucion_trabajos)
                            <div class="mb-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Lugar de ejecución</span>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $licitacion->lugar_ejecucion_trabajos }}</p>
                            </div>
                        @endif

                        @if($licitacion->requiere_visita_terreno)
                            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    ⚠️ Esta licitación requiere visita a terreno
                                </p>
                                @if($licitacion->fecha_visita_terreno)
                                    <p class="text-sm text-yellow-600 dark:text-yellow-300 mt-1">
                                        Fecha: {{ \Carbon\Carbon::parse($licitacion->fecha_visita_terreno)->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
