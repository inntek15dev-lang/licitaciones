<?php

namespace App\Livewire\Contratista\Ofertas;

use App\Models\Oferta;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $filtroEstado = '';
    public $busqueda = '';

    protected $queryString = [
        'filtroEstado' => ['except' => ''],
        'busqueda' => ['except' => ''],
    ];

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
        $this->busqueda = '';
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        $ofertas = Oferta::with(['licitacion.principal', 'documentos'])
            ->where('contratista_id', $user->empresa_contratista_id)
            ->when($this->filtroEstado, function ($query) {
                $query->where('estado_oferta', $this->filtroEstado);
            })
            ->when($this->busqueda, function ($query) {
                $query->whereHas('licitacion', function ($q) {
                    $q->where('titulo', 'like', '%' . $this->busqueda . '%')
                      ->orWhere('codigo_licitacion', 'like', '%' . $this->busqueda . '%');
                });
            })
            ->orderByDesc('fecha_presentacion')
            ->paginate(10);

        $estados = Oferta::ESTADOS;

        return view('livewire.contratista.ofertas.index', [
            'ofertas' => $ofertas,
            'estados' => $estados,
        ]);
    }
}
