<?php

namespace App\Livewire\Admin\Licitaciones;

use App\Models\Licitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Licitacion $licitacion;
    public $observacion = '';
    public $showObservacionModal = false;

    public function mount($id)
    {
        $this->licitacion = Licitacion::with([
            'principal',
            'creador',
            'categorias',
            'documentos',
            'requisitosDocumentos',
            'ofertas.contratista',
            'observaciones.revisor',
        ])->findOrFail($id);
    }

    public function aprobar()
    {
        if ($this->licitacion->estado !== 'lista_para_publicar') {
            session()->flash('error', 'Solo se pueden aprobar licitaciones listas para publicar.');
            return;
        }

        $this->licitacion->update([
            'estado' => 'publicada',
            'fecha_publicacion' => now(),
            'usuario_revisor_ryce_id' => auth()->id(),
        ]);

        \App\Models\Notificacion::crearNotificacion(
            $this->licitacion->creador_id,
            'licitacion_publicada',
            'Tu licitación ha sido publicada',
            "La licitación \"{$this->licitacion->titulo}\" ha sido aprobada y publicada.",
            $this->licitacion->id,
            'licitacion'
        );

        session()->flash('message', 'Licitación aprobada y publicada correctamente.');
        return redirect()->route('admin.licitaciones');
    }

    public function openObservacionModal()
    {
        $this->showObservacionModal = true;
    }

    public function closeObservacionModal()
    {
        $this->showObservacionModal = false;
        $this->observacion = '';
    }

    public function observar()
    {
        $this->validate([
            'observacion' => 'required|string|min:10',
        ], [
            'observacion.required' => 'Debe ingresar una observación.',
            'observacion.min' => 'La observación debe tener al menos 10 caracteres.',
        ]);

        // Guardar en historial de observaciones
        \App\Models\ObservacionLicitacion::create([
            'licitacion_id' => $this->licitacion->id,
            'usuario_revisor_id' => auth()->id(),
            'observacion' => $this->observacion,
        ]);

        $this->licitacion->update([
            'estado' => 'observada_por_ryce',
            'comentarios_revision_ryce' => $this->observacion,
            'usuario_revisor_ryce_id' => auth()->id(),
        ]);

        // Enviar email de notificación al creador de la licitación
        try {
            $creador = $this->licitacion->creador;
            if ($creador && $creador->email) {
                \Illuminate\Support\Facades\Mail::to($creador->email)
                    ->send(new \App\Mail\LicitacionObservadaMail(
                        $this->licitacion,
                        $this->observacion,
                        auth()->user()->name
                    ));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error enviando email de observación: ' . $e->getMessage());
        }

        \App\Models\Notificacion::crearNotificacion(
            $this->licitacion->usuario_creador_id,
            'licitacion_observada',
            'Tu licitación tiene observaciones',
            "La licitación \"{$this->licitacion->titulo}\" requiere correcciones: {$this->observacion}",
            $this->licitacion->id,
            'licitacion'
        );

        session()->flash('message', 'Licitación marcada como observada y notificación enviada.');
        return redirect()->route('admin.licitaciones');
    }

    /**
     * Admin RyCE subsana directamente la licitación observada.
     * Limpia las observaciones y cambia el estado a lista_para_publicar.
     */
    public function subsanar()
    {
        if ($this->licitacion->estado !== 'observada_por_ryce') {
            session()->flash('error', 'Solo se pueden subsanar licitaciones observadas.');
            return;
        }

        $this->licitacion->update([
            'estado' => 'lista_para_publicar',
            'comentarios_revision_ryce' => null,
            'usuario_revisor_ryce_id' => auth()->id(),
        ]);

        \App\Models\Notificacion::crearNotificacion(
            $this->licitacion->usuario_creador_id,
            'licitacion_subsanada',
            'Tu licitación ha sido subsanada',
            "La licitación \"{$this->licitacion->titulo}\" ha sido corregida por RyCE y está lista para revisión final.",
            $this->licitacion->id,
            'licitacion'
        );

        session()->flash('message', 'Licitación subsanada y lista para revisión final.');
        $this->licitacion->refresh();
    }

    public function render()
    {
        return view('livewire.admin.licitaciones.show');
    }
}
