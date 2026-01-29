<?php

namespace App\Livewire\Admin\Licitaciones;

use App\Models\Licitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterEstado = '';
    public $filterPrincipal = '';
    public $filterPrecalificacion = ''; // '', 'si', 'no'

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function aprobarLicitacion($id)
    {
        $licitacion = Licitacion::findOrFail($id);

        if ($licitacion->estado !== 'lista_para_publicar') {
            session()->flash('error', 'Solo se pueden aprobar licitaciones listas para publicar.');
            return;
        }

        $licitacion->update([
            'estado' => 'publicada',
            'fecha_publicacion' => now(),
            'aprobador_id' => auth()->id(),
        ]);

        // Crear notificación para el creador
        \App\Models\Notificacion::crearNotificacion(
            $licitacion->creador_id,
            'licitacion_publicada',
            'Tu licitación ha sido publicada',
            "La licitación \"{$licitacion->titulo}\" ha sido aprobada y publicada.",
            $licitacion->id,
            'licitacion'
        );

        session()->flash('message', 'Licitación aprobada y publicada correctamente.');
    }

    public function observarLicitacion($id, $observacion = '')
    {
        $licitacion = Licitacion::findOrFail($id);

        $licitacion->update([
            'estado' => 'observada_por_ryce',
            'observaciones_ryce' => $observacion ?: 'Requiere revisión adicional.',
        ]);

        // Crear notificación para el creador
        \App\Models\Notificacion::crearNotificacion(
            $licitacion->creador_id,
            'licitacion_observada',
            'Tu licitación tiene observaciones',
            "La licitación \"{$licitacion->titulo}\" requiere correcciones.",
            $licitacion->id,
            'licitacion'
        );

        session()->flash('message', 'Licitación marcada como observada.');
    }

    public function render()
    {
        $licitaciones = Licitacion::query()
            ->with(['principal', 'creador', 'categorias'])
            ->withCount('ofertas')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('titulo', 'like', '%' . $this->search . '%')
                        ->orWhere('codigo_licitacion', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterEstado, function ($query) {
                $query->where('estado', $this->filterEstado);
            })
            ->when($this->filterPrincipal, function ($query) {
                $query->where('principal_id', $this->filterPrincipal);
            })
            ->when($this->filterPrecalificacion !== '', function ($query) {
                if ($this->filterPrecalificacion === 'si') {
                    $query->where('requiere_precalificacion', true);
                } elseif ($this->filterPrecalificacion === 'no') {
                    $query->where(function ($q) {
                        $q->where('requiere_precalificacion', false)
                            ->orWhereNull('requiere_precalificacion');
                    });
                }
            })
            ->orderByRaw("
                CASE estado
                    WHEN 'lista_para_publicar' THEN 1
                    WHEN 'observada_por_ryce' THEN 2
                    WHEN 'publicada' THEN 3
                    WHEN 'borrador' THEN 4
                    ELSE 5
                END ASC
            ")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Conteos por estado
        $estadosCounts = Licitacion::selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // Lista de principales para filtro
        $principales = \App\Models\EmpresaPrincipal::where('activo', true)
            ->orderBy('razon_social')
            ->get();

        return view('livewire.admin.licitaciones.index', [
            'licitaciones' => $licitaciones,
            'estadosCounts' => $estadosCounts,
            'estados' => Licitacion::ESTADOS,
            'principales' => $principales,
        ]);
    }
}
