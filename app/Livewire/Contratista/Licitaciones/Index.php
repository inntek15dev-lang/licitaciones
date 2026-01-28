<?php

namespace App\Livewire\Contratista\Licitaciones;

use App\Models\CategoriaLicitacion;
use App\Models\Licitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $categoria = '';
    public $tipo = '';
    public $ordenar = 'recientes';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoria' => ['except' => ''],
        'tipo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoria()
    {
        $this->resetPage();
    }

    public function updatingTipo()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->reset(['search', 'categoria', 'tipo', 'ordenar']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Licitacion::query()
            ->where('estado', 'publicada')
            ->where('tipo_licitacion', 'publica')
            ->where('fecha_cierre_recepcion_ofertas', '>=', now())
            ->with(['principal', 'categorias']);

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titulo', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo_licitacion', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion_corta', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por categoría
        if ($this->categoria) {
            $query->whereHas('categorias', function ($q) {
                $q->where('categorias_licitaciones.id', $this->categoria);
            });
        }

        // Filtro por tipo
        if ($this->tipo) {
            $query->where('tipo_licitacion', $this->tipo);
        }

        // Ordenamiento
        switch ($this->ordenar) {
            case 'cierre_proximo':
                $query->orderBy('fecha_cierre_recepcion_ofertas', 'asc');
                break;
            case 'presupuesto_mayor':
                $query->orderBy('presupuesto_referencial', 'desc');
                break;
            case 'presupuesto_menor':
                $query->orderBy('presupuesto_referencial', 'asc');
                break;
            default:
                $query->orderBy('fecha_publicacion', 'desc');
        }

        $licitaciones = $query->paginate(10);

        // Calcular días restantes para cada licitación
        $licitaciones->getCollection()->transform(function ($licitacion) {
            $licitacion->dias_restantes = now()->diffInDays($licitacion->fecha_cierre_recepcion_ofertas, false);
            return $licitacion;
        });

        $categorias = CategoriaLicitacion::orderBy('nombre_categoria')->get();

        return view('livewire.contratista.licitaciones.index', [
            'licitaciones' => $licitaciones,
            'categorias' => $categorias,
        ]);
    }
}
