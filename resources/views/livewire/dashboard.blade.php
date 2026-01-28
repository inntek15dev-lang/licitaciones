<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header con saludo -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                ¡Bienvenido, {{ $user->nombre_completo ?? $user->name }}!
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @switch($role)
                    @case('admin_plataforma')
                        Panel de Administración RyCE
                        @break
                    @case('usuario_principal')
                        Portal de Empresa Principal
                        @break
                    @case('usuario_contratista')
                        Portal de Contratista
                        @break
                    @default
                        Sistema de Licitaciones
                @endswitch
            </p>
        </div>

        <!-- Notificaciones -->
        @if($notificacionesNoLeidas > 0)
        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                        Tienes <span class="font-bold">{{ $notificacionesNoLeidas }}</span> notificación(es) sin leer
                    </p>
                </div>
                <a href="#" class="text-sm font-semibold text-amber-700 dark:text-amber-300 hover:underline">
                    Ver todas →
                </a>
            </div>
        </div>
        @endif

        <!-- Stats Cards - Admin -->
        @if($role === 'admin_plataforma')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Licitaciones Pendientes -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="relative z-10">
                    <p class="text-blue-100 text-sm font-medium">Licitaciones Pendientes</p>
                    <p class="text-4xl font-bold mt-2">{{ $stats['licitaciones_pendientes'] ?? 0 }}</p>
                    <p class="text-blue-200 text-xs mt-2">Por aprobar</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-20">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>

            <!-- Ofertas Por Precalificar -->
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
                <div class="relative z-10">
                    <p class="text-amber-100 text-sm font-medium">Ofertas Por Precalificar</p>
                    <p class="text-4xl font-bold mt-2">{{ $stats['ofertas_por_precalificar'] ?? 0 }}</p>
                    <p class="text-amber-200 text-xs mt-2">Pendientes de revisión</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-20">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Consultas Pendientes -->
            <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="relative z-10">
                    <p class="text-purple-100 text-sm font-medium">Consultas Sin Responder</p>
                    <p class="text-4xl font-bold mt-2">{{ $stats['consultas_pendientes'] ?? 0 }}</p>
                    <p class="text-purple-200 text-xs mt-2">Preguntas pendientes</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-20">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
            </div>

            <!-- Empresas Principales -->
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="relative z-10">
                    <p class="text-emerald-100 text-sm font-medium">Empresas Principales</p>
                    <p class="text-4xl font-bold mt-2">{{ $stats['empresas_principales'] ?? 0 }}</p>
                    <p class="text-emerald-200 text-xs mt-2">Clientes activos</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-20">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <!-- Empresas Contratistas -->
            <div class="relative overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="relative z-10">
                    <p class="text-sky-100 text-sm font-medium">Empresas Contratistas</p>
                    <p class="text-4xl font-bold mt-2">{{ $stats['empresas_contratistas'] ?? 0 }}</p>
                    <p class="text-sky-200 text-xs mt-2">Proveedores registrados</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-20">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Licitaciones Activas -->
            <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="relative z-10">
                    <p class="text-green-100 text-sm font-medium">Licitaciones Activas</p>
                    <p class="text-4xl font-bold mt-2">{{ $stats['licitaciones_publicadas'] ?? 0 }}</p>
                    <p class="text-green-200 text-xs mt-2">En proceso</p>
                </div>
                <div class="absolute -right-4 -bottom-4 opacity-20">
                    <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Cards - Principal -->
        @if($role === 'usuario_principal')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-indigo-100 text-sm font-medium">Total Licitaciones</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['total_licitaciones'] ?? 0 }}</p>
            </div>
            <div class="relative overflow-hidden bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-gray-100 text-sm font-medium">Borradores</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['borradores'] ?? 0 }}</p>
            </div>
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-emerald-100 text-sm font-medium">Publicadas</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['publicadas'] ?? 0 }}</p>
            </div>
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-amber-100 text-sm font-medium">Ofertas Recibidas</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['ofertas_recibidas'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Acciones Rápidas - Principal -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones Rápidas</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('principal.licitaciones.create') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors group">
                    <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Nueva Licitación</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Crear nueva licitación</p>
                    </div>
                </a>
                <a href="{{ route('principal.licitaciones') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors group">
                    <div class="h-12 w-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Mis Licitaciones</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ver todas mis licitaciones</p>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition-colors group">
                    <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Consultas</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stats['consultas_pendientes'] ?? 0 }} pendientes</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- Stats Cards - Contratista -->
        @if($role === 'usuario_contratista')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="relative overflow-hidden bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-cyan-100 text-sm font-medium">Licitaciones Disponibles</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['licitaciones_disponibles'] ?? 0 }}</p>
            </div>
            <div class="relative overflow-hidden bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-violet-100 text-sm font-medium">Mis Postulaciones</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['mis_postulaciones'] ?? 0 }}</p>
            </div>
            <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-emerald-100 text-sm font-medium">Precalificadas</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['ofertas_precalificadas'] ?? 0 }}</p>
            </div>
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
                <p class="text-amber-100 text-sm font-medium">Adjudicadas</p>
                <p class="text-4xl font-bold mt-2">{{ $stats['ofertas_adjudicadas'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Acciones Rápidas - Contratista -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones Rápidas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="#" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-cyan-500 dark:hover:border-cyan-500 transition-colors group">
                    <div class="h-12 w-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Buscar Licitaciones</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Explorar oportunidades</p>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-violet-500 dark:hover:border-violet-500 transition-colors group">
                    <div class="h-12 w-12 bg-violet-100 dark:bg-violet-900/30 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Mis Postulaciones</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ver estado de ofertas</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- Tabla de Licitaciones Recientes -->
        @if(count($licitacionesRecientes) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    @if($role === 'usuario_contratista')
                        Licitaciones Disponibles
                    @else
                        Licitaciones Recientes
                    @endif
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                            @if($role !== 'usuario_principal')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Empresa</th>
                            @endif
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($licitacionesRecientes as $licitacion)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                    {{ $licitacion->codigo_licitacion }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($licitacion->titulo, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estadoClasses = [
                                        'borrador' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'lista_para_publicar' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'observada_por_ryce' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                        'publicada' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'adjudicada' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                    $class = $estadoClasses[$licitacion->estado] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                    {{ \App\Models\Licitacion::ESTADOS[$licitacion->estado] ?? $licitacion->estado }}
                                </span>
                            </td>
                            @if($role !== 'usuario_principal')
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $licitacion->principal?->razon_social ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if($role === 'admin_plataforma')
                                    <a href="{{ route('admin.licitaciones.show', $licitacion->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Ver detalles →
                                    </a>
                                @elseif($role === 'usuario_principal')
                                    <a href="{{ route('principal.licitaciones.show', $licitacion->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Ver detalles →
                                    </a>
                                @else
                                    <a href="{{ route('contratista.licitaciones.show', $licitacion->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">
                                        Ver detalles →
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay licitaciones</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                @if($role === 'usuario_principal')
                    Comienza creando tu primera licitación
                @else
                    No hay licitaciones disponibles en este momento
                @endif
            </p>
        </div>
        @endif

    </div>
</div>
