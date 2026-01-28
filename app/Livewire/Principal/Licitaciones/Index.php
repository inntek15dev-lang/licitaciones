<?php

namespace App\Livewire\Principal\Licitaciones;

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterEstado()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $empresaId = $user->empresa_principal_id;

        $licitaciones = Licitacion::query()
            ->where('principal_id', $empresaId)
            ->with('categorias', 'creador')
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $estadosCounts = Licitacion::where('principal_id', $empresaId)
            ->selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return view('livewire.principal.licitaciones.index', [
            'licitaciones' => $licitaciones,
            'estadosCounts' => $estadosCounts,
            'estados' => Licitacion::ESTADOS,
        ]);
    }
}
