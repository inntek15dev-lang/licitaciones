<?php

namespace App\Livewire\Principal\Licitaciones;

use App\Models\Licitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Licitacion $licitacion;

    public function mount(Licitacion $licitacion)
    {
        $user = auth()->user();
        
        // Verificar que la licitación pertenezca a la empresa del usuario
        if ($licitacion->principal_id !== $user->empresa_principal_id) {
            abort(403, 'No tienes permiso para ver esta licitación.');
        }

        $this->licitacion = $licitacion->load([
            'principal',
            'categorias',
            'documentos',
            'requisitosDocumentos',
            'ofertas.contratista',
            'consultasRespuestas.contratista',
        ]);
    }

    public function render()
    {
        return view('livewire.principal.licitaciones.show');
    }
}
