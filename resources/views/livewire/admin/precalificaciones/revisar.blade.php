<div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.precalificaciones') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Revisar Precalificación</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $precalificacion->licitacion->titulo ?? 'Licitación' }}</p>
            </div>
        </div>
        
        <!-- Estado Actual -->
        <div>
            @switch($precalificacion->estado)
                @case('pendiente')
                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium">⏳ Pendiente</span>
                    @break
                @case('aprobada')
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">✅ Aprobada</span>
                    @break
                @case('rechazada')
                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium">❌ Rechazada</span>
                    @break
                @case('rectificando')
                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium">🔄 Rectificando</span>
                    @break
            @endswitch
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Contratista -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🏢 Contratista</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Razón Social:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $precalificacion->contratista->razon_social ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">RUT:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $precalificacion->contratista->rut ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Email:</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $precalificacion->contratista->email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Comentarios del Contratista -->
            @if($precalificacion->comentarios_contratista)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">💬 Comentarios del Contratista</h2>
                <p class="text-gray-700 dark:text-gray-300">{{ $precalificacion->comentarios_contratista }}</p>
            </div>
            @endif

            <!-- Rectificación -->
            @if($precalificacion->comentarios_rectificacion)
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl shadow-lg p-6 border border-blue-200 dark:border-blue-700">
                <h2 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-4">🔄 Rectificación</h2>
                <p class="text-blue-800 dark:text-blue-300">{{ $precalificacion->comentarios_rectificacion }}</p>
            </div>
            @endif

            <!-- Documentos Adjuntos -->
            @if($documentos->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📄 Documentos Adjuntos</h2>
                <div class="space-y-2">
                    @foreach($documentos as $doc)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ str_replace('[PRECAL-' . $precalificacion->contratista_id . '] ', '', $doc->nombre_documento) }}</span>
                        @if($doc->ruta_archivo)
                        <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank" class="text-violet-600 hover:text-violet-800 text-sm font-medium">
                            Descargar
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Botones de Acción -->
            @if(in_array($precalificacion->estado, ['pendiente', 'rectificando']))
            <div class="flex gap-4">
                <button wire:click="abrirModalAprobar" class="flex-1 px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold text-lg hover:from-green-600 hover:to-emerald-700 transition shadow-lg">
                    ✅ Aprobar Precalificación
                </button>
                <button wire:click="abrirModalRechazar" class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-semibold text-lg hover:from-red-600 hover:to-rose-700 transition shadow-lg">
                    ❌ Rechazar
                </button>
            </div>
            @endif

            <!-- Historial -->
            @if($precalificacion->fecha_resolucion)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Última Resolución</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $precalificacion->fecha_resolucion->format('d/m/Y H:i') }} 
                    por {{ $precalificacion->revisor->name ?? 'Usuario' }}
                    ({{ $precalificacion->tipo_revisor === 'ryce' ? 'RyCE' : 'Principal' }})
                </p>
                @if($precalificacion->motivo_rechazo)
                <div class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-300"><strong>Motivo:</strong> {{ $precalificacion->motivo_rechazo }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Requisitos -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">📋 Requisitos Solicitados</h3>
                @if($requisitos->count() > 0)
                <ul class="space-y-2">
                    @foreach($requisitos as $requisito)
                    <li class="flex items-start gap-2 text-sm">
                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $requisito->es_obligatorio ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $requisito->es_obligatorio ? 'Oblig.' : 'Opc.' }}
                        </span>
                        <span class="text-gray-700 dark:text-gray-300">{{ $requisito->nombre_requisito }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-gray-500">No hay requisitos específicos.</p>
                @endif
            </div>

            <!-- Info Licitación -->
            <div class="bg-gradient-to-br from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-2xl p-6 border border-violet-200 dark:border-violet-800">
                <h3 class="text-sm font-semibold text-violet-900 dark:text-violet-200 mb-4">📌 Licitación</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-violet-700 dark:text-violet-300 block">Código:</span>
                        <span class="font-medium text-violet-900 dark:text-violet-100">{{ $precalificacion->licitacion->codigo_licitacion ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-violet-700 dark:text-violet-300 block">Responsable:</span>
                        <span class="font-medium text-violet-900 dark:text-violet-100">
                            {{ $precalificacion->licitacion->responsable_precalificacion === 'ryce' ? 'Solo RyCE' : ($precalificacion->licitacion->responsable_precalificacion === 'principal' ? 'Solo Principal' : 'Ambos') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Aprobar -->
    @if($showModalAprobar)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">✅ Confirmar Aprobación</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                ¿Estás seguro de aprobar la precalificación de <strong>{{ $precalificacion->contratista->razon_social ?? 'este contratista' }}</strong>?
            </p>
            <p class="text-sm text-green-600 mb-6">El contratista podrá postular a esta licitación.</p>
            <div class="flex gap-3">
                <button wire:click="aprobar" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">
                    Sí, Aprobar
                </button>
                <button wire:click="cerrarModales" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Rechazar -->
    @if($showModalRechazar)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">❌ Rechazar Precalificación</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Indica el motivo del rechazo. El contratista podrá rectificar y enviar nuevamente.
            </p>
            <textarea wire:model="motivoRechazo" rows="4" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white mb-2" placeholder="Motivo del rechazo..."></textarea>
            @error('motivoRechazo') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            <div class="flex gap-3 mt-4">
                <button wire:click="rechazar" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                    Rechazar
                </button>
                <button wire:click="cerrarModales" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
