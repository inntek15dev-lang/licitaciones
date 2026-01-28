<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navegación -->
        <div class="mb-6">
            <a href="{{ route('contratista.licitaciones.show', $licitacion) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Licitación
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-white/20 text-white">
                        {{ $licitacion->codigo_licitacion }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Postular Oferta</h1>
                <p class="text-emerald-100">{{ $licitacion->titulo }}</p>
            </div>

            <!-- Info de la licitación -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50">
                <div class="text-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Empresa</span>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $licitacion->principal->razon_social ?? 'N/A' }}</p>
                </div>
                <div class="text-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Presupuesto Ref.</span>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">
                        @if($licitacion->presupuesto_referencial)
                            {{ $licitacion->moneda_presupuesto ?? 'CLP' }} {{ number_format($licitacion->presupuesto_referencial, 0, ',', '.') }}
                        @else
                            No especificado
                        @endif
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Cierre Ofertas</span>
                    <p class="font-semibold text-red-600 dark:text-red-400 text-sm">
                        {{ $licitacion->fecha_cierre_recepcion_ofertas ? \Carbon\Carbon::parse($licitacion->fecha_cierre_recepcion_ofertas)->format('d/m/Y H:i') : 'N/A' }}
                    </p>
                </div>
                <div class="text-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Tipo</span>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm capitalize">{{ $licitacion->tipo_licitacion }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form wire:submit="enviarOferta" class="space-y-6">
            <!-- Sección 1: Oferta Económica -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center mr-3 text-emerald-600 dark:text-emerald-400 font-bold text-sm">1</span>
                    Oferta Económica
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Monto -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Monto de la Oferta <span class="text-red-500">*</span>
                        </label>
                        <div class="flex rounded-lg shadow-sm">
                            <select wire:model="monedaOferta" class="rounded-l-lg border-r-0 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="CLP">CLP</option>
                                <option value="UF">UF</option>
                                <option value="USD">USD</option>
                            </select>
                            <input type="number" 
                                   wire:model="montoOferta" 
                                   step="0.01"
                                   class="flex-1 rounded-r-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                   placeholder="Ingrese el monto">
                        </div>
                        @error('montoOferta') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Validez -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Validez (días) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               wire:model="validezDias" 
                               min="1" 
                               max="365"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                        @error('validezDias') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Sección 2: Documentos -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center mr-3 text-emerald-600 dark:text-emerald-400 font-bold text-sm">2</span>
                    Documentos Requeridos
                </h2>

                @if(count($documentosInfo) > 0)
                    <div class="space-y-4">
                        @foreach($documentosInfo as $index => $info)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $info['obligatorio'] ? 'bg-red-50/50 dark:bg-red-900/10' : 'bg-gray-50 dark:bg-gray-700/30' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        @if($info['obligatorio'])
                                            <span class="w-6 h-6 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-red-600 dark:text-red-400 text-xs font-bold">*</span>
                                            </span>
                                        @else
                                            <span class="w-6 h-6 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-gray-400 text-xs">○</span>
                                            </span>
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $info['nombre'] }}</span>
                                        @if($info['obligatorio'])
                                            <span class="ml-2 text-xs text-red-500">(Obligatorio)</span>
                                        @else
                                            <span class="ml-2 text-xs text-gray-400">(Opcional)</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="relative">
                                    <input type="file" 
                                           wire:model="documentosArchivos.{{ $index }}" 
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.png"
                                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-lg file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-emerald-50 file:text-emerald-700
                                                  hover:file:bg-emerald-100
                                                  dark:file:bg-emerald-900 dark:file:text-emerald-300">
                                    
                                    <div wire:loading wire:target="documentosArchivos.{{ $index }}" class="mt-2">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="animate-spin h-4 w-4 mr-2 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Subiendo archivo...
                                        </div>
                                    </div>

                                    @if(isset($documentosArchivos[$index]) && $documentosArchivos[$index])
                                        <div class="mt-2 flex items-center text-sm text-green-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ $documentosArchivos[$index]->getClientOriginalName() }}
                                        </div>
                                    @endif
                                </div>
                                
                                @error("documentosArchivos.{$index}") 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Esta licitación no tiene requisitos documentales específicos.</p>
                    </div>
                @endif
            </div>

            <!-- Sección 3: Comentarios -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center mr-3 text-emerald-600 dark:text-emerald-400 font-bold text-sm">3</span>
                    Comentarios Adicionales
                </h2>

                <textarea wire:model="comentarios" 
                          rows="4"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                          placeholder="Ingrese cualquier información adicional que desee compartir con el mandante..."></textarea>
                @error('comentarios') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('contratista.licitaciones.show', $licitacion) }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg wire:loading wire:target="enviarOferta" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="enviarOferta">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Enviar Oferta
                </button>
            </div>
        </form>
    </div>
</div>
