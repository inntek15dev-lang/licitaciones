<?php

namespace App\Livewire\Admin\Precalificaciones;

use App\Models\PrecalificacionContratista;
use App\Models\Licitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $filtroEstado = '';
    public $filtroLicitacion = '';
    public $busqueda = '';

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->filtroEstado = '';
        $this->filtroLicitacion = '';
        $this->busqueda = '';
    }

    public function render()
    {
        $query = PrecalificacionContratista::with(['licitacion', 'contratista', 'revisor'])
            ->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        // Filtro por licitación
        if ($this->filtroLicitacion) {
            $query->where('licitacion_id', $this->filtroLicitacion);
        }

        // Búsqueda
        if ($this->busqueda) {
            $query->whereHas('contratista', function($q) {
                $q->where('razon_social', 'like', '%' . $this->busqueda . '%')
                  ->orWhere('rut', 'like', '%' . $this->busqueda . '%');
            });
        }

        $precalificaciones = $query->paginate(15);
        $licitaciones = Licitacion::where('requiere_precalificacion', true)->orderBy('titulo')->get();

        // Estadísticas
        $pendientes = PrecalificacionContratista::pendientes()->count();
        $rectificando = PrecalificacionContratista::rectificando()->count();

        return view('livewire.admin.precalificaciones.index', [
            'precalificaciones' => $precalificaciones,
            'licitaciones' => $licitaciones,
            'pendientes' => $pendientes,
            'rectificando' => $rectificando,
        ]);
    }
}
