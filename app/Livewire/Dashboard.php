<?php

namespace App\Livewire;

use App\Models\EmpresaContratista;
use App\Models\EmpresaPrincipal;
use App\Models\Licitacion;
use App\Models\Notificacion;
use App\Models\Oferta;
use App\Models\ConsultaRespuestaLicitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $stats = [];
    public $licitacionesRecientes = [];
    public $notificacionesNoLeidas = 0;

    public function mount()
    {
        $user = auth()->user();
        $this->notificacionesNoLeidas = Notificacion::where('usuario_destino_id', $user->id)
            ->noLeidas()
            ->count();

        if ($user->hasRole('admin_plataforma')) {
            $this->loadAdminStats();
        } elseif ($user->hasRole('usuario_principal')) {
            $this->loadPrincipalStats();
        } elseif ($user->hasRole('usuario_contratista')) {
            $this->loadContratistaStats();
        }
    }

    /**
     * Estadísticas para Admin RyCE
     */
    private function loadAdminStats()
    {
        $this->stats = [
            'licitaciones_pendientes' => Licitacion::where('estado', 'lista_para_publicar')->count(),
            'licitaciones_publicadas' => Licitacion::where('estado', 'publicada')->count(),
            'ofertas_por_precalificar' => Oferta::where('estado_oferta', 'pendiente_precalificacion_ryce')->count(),
            'consultas_pendientes' => ConsultaRespuestaLicitacion::pendientes()->count(),
            'empresas_principales' => EmpresaPrincipal::where('activo', true)->count(),
            'empresas_contratistas' => EmpresaContratista::where('activo', true)->count(),
        ];

        $this->licitacionesRecientes = Licitacion::with('principal', 'creador')
            ->whereIn('estado', ['lista_para_publicar', 'publicada', 'observada_por_ryce'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Estadísticas para Usuario Principal
     */
    private function loadPrincipalStats()
    {
        $user = auth()->user();
        $empresaId = $user->empresa_principal_id;

        $licitacionesPropias = Licitacion::where('principal_id', $empresaId);

        $this->stats = [
            'total_licitaciones' => (clone $licitacionesPropias)->count(),
            'borradores' => (clone $licitacionesPropias)->where('estado', 'borrador')->count(),
            'publicadas' => (clone $licitacionesPropias)->where('estado', 'publicada')->count(),
            'ofertas_recibidas' => Oferta::whereHas('licitacion', function($q) use ($empresaId) {
                $q->where('principal_id', $empresaId);
            })->where('estado_oferta', 'precalificada_por_ryce')->count(),
            'consultas_pendientes' => ConsultaRespuestaLicitacion::whereHas('licitacion', function($q) use ($empresaId) {
                $q->where('principal_id', $empresaId);
            })->pendientes()->count(),
        ];

        $this->licitacionesRecientes = Licitacion::where('principal_id', $empresaId)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Estadísticas para Usuario Contratista
     */
    private function loadContratistaStats()
    {
        $user = auth()->user();
        $empresaId = $user->empresa_contratista_id;

        $this->stats = [
            'licitaciones_disponibles' => Licitacion::where('estado', 'publicada')
                ->where('tipo_licitacion', 'publica')
                ->count(),
            'mis_postulaciones' => Oferta::where('contratista_id', $empresaId)->count(),
            'ofertas_precalificadas' => Oferta::where('contratista_id', $empresaId)
                ->where('estado_oferta', 'precalificada_por_ryce')
                ->count(),
            'ofertas_adjudicadas' => Oferta::where('contratista_id', $empresaId)
                ->where('estado_oferta', 'adjudicada')
                ->count(),
        ];

        // Licitaciones públicas activas
        $this->licitacionesRecientes = Licitacion::with('principal', 'categorias')
            ->where('estado', 'publicada')
            ->where('tipo_licitacion', 'publica')
            ->orderBy('fecha_cierre_recepcion_ofertas', 'asc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        $user = auth()->user();
        $role = $user->roles->first()?->name ?? 'sin_rol';

        return view('livewire.dashboard', [
            'user' => $user,
            'role' => $role,
        ]);
    }
}

