<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navegación -->
        <div class="mb-6">
            <a href="{{ route('principal.licitaciones') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Mis Licitaciones
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-white/20 text-white">
                                {{ $licitacion->codigo_licitacion }}
                            </span>
                            @php
                                $estadoColors = [
                                    'borrador' => 'bg-gray-400 text-gray-900',
                                    'lista_para_publicar' => 'bg-blue-400 text-blue-900',
                                    'observada_por_ryce' => 'bg-orange-400 text-orange-900',
                                    'publicada' => 'bg-green-400 text-green-900',
                                    'cerrada_ofertas' => 'bg-yellow-400 text-yellow-900',
                                    'adjudicada' => 'bg-purple-400 text-purple-900',
                                ];
                                $colorClass = $estadoColors[$licitacion->estado] ?? 'bg-gray-300 text-gray-800';
                            @endphp
                            <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $colorClass }}">
                                {{ \App\Models\Licitacion::ESTADOS[$licitacion->estado] ?? $licitacion->estado }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            {{ $licitacion->titulo }}
                        </h1>
                        <p class="text-indigo-100">
                            Creada el {{ $licitacion->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    @if($licitacion->presupuesto_referencial)
                        <div class="text-right bg-white/10 rounded-lg px-6 py-4">
                            <span class="text-indigo-100 text-sm">Presupuesto Referencial</span>
                            <p class="text-3xl font-bold text-white">
                                {{ $licitacion->moneda_presupuesto ?? 'CLP' }} {{ number_format($licitacion->presupuesto_referencial, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Observaciones de RyCE (si aplica) -->
            @if($licitacion->estado === 'observada_por_ryce' && $licitacion->comentarios_revision_ryce)
                <div class="bg-orange-50 dark:bg-orange-900/30 border-l-4 border-orange-500 p-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-orange-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200">Observaciones de RyCE</h3>
                            <p class="mt-1 text-orange-700 dark:text-orange-300">{{ $licitacion->comentarios_revision_ryce }}</p>
                            <p class="mt-3 text-sm text-orange-600 dark:text-orange-400">
                                Por favor, corrija las observaciones y envíe nuevamente para revisión.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Fechas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 bg-gray-50 dark:bg-gray-700/50">
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Consultas hasta</span>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $licitacion->fecha_cierre_consultas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_consultas)->format('d/m/Y') : '-' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Cierre Ofertas</span>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $licitacion->fecha_cierre_recepcion_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y H:i') : '-' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Adjudicación Est.</span>
                    <p class="font-semibold text-gray-900 dark:text-white">
                        {{ $licitacion->fecha_adjudicacion_estimada ? \Carbon\Carbon::parse($licitacion->fecha_adjudicacion_estimada)->format('d/m/Y') : '-' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Ofertas Recibidas</span>
                    <p class="font-semibold text-2xl text-indigo-600 dark:text-indigo-400">
                        {{ $licitacion->ofertas->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Descripción -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Descripción</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! nl2br(e($licitacion->descripcion_corta)) !!}
                        @if($licitacion->descripcion_larga)
                            <hr class="my-4">
                            {!! nl2br(e($licitacion->descripcion_larga)) !!}
                        @endif
                    </div>

                    @if($licitacion->categorias->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-3">CATEGORÍAS</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($licitacion->categorias as $cat)
                                    <span class="px-3 py-1 rounded-full text-sm bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
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
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Documentos Subidos</h2>
                        <div class="space-y-3">
                            @foreach($licitacion->documentos as $doc)
                                <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank"
                                   class="flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $doc->nombre_documento }}</p>
                                        <p class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $doc->tipo_documento) }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Ofertas Recibidas -->
                @if($licitacion->ofertas->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Ofertas Recibidas</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contratista</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($licitacion->ofertas as $oferta)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $oferta->contratista->razon_social ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold text-green-600">
                                                ${{ number_format($oferta->monto_oferta ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ ucfirst(str_replace('_', ' ', $oferta->estado)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-500">
                                                {{ $oferta->created_at->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Columna Lateral -->
            <div class="space-y-6">
                <!-- Acciones -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Acciones</h3>
                    
                    @if($licitacion->estado === 'borrador' || $licitacion->estado === 'observada_por_ryce')
                        <a href="{{ route('principal.licitaciones.edit', $licitacion) }}"
                           class="w-full mb-3 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar Licitación
                        </a>
                    @endif

                    <a href="{{ route('principal.licitaciones') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                        Volver al Listado
                    </a>
                </div>

                <!-- Requisitos -->
                @if($licitacion->requisitosDocumentos->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Requisitos para Contratistas</h3>
                        <ul class="space-y-2">
                            @foreach($licitacion->requisitosDocumentos->sortBy('orden') as $req)
                                <li class="flex items-start">
                                    @if($req->es_obligatorio)
                                        <span class="flex-shrink-0 w-5 h-5 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-red-600 text-xs font-bold">*</span>
                                        </span>
                                    @else
                                        <span class="flex-shrink-0 w-5 h-5 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-gray-400 text-xs">○</span>
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $req->nombre_requisito }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
