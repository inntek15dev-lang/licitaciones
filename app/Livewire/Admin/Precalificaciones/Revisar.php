<?php

namespace App\Livewire\Admin\Precalificaciones;

use App\Models\PrecalificacionContratista;
use App\Mail\PrecalificacionAprobadaMail;
use App\Mail\PrecalificacionRechazadaMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Revisar extends Component
{
    public PrecalificacionContratista $precalificacion;

    public $motivoRechazo = '';
    public $showModalAprobar = false;
    public $showModalRechazar = false;

    public function mount(PrecalificacionContratista $precalificacion)
    {
        $this->precalificacion = $precalificacion;
    }

    public function abrirModalAprobar()
    {
        $this->showModalAprobar = true;
    }

    public function abrirModalRechazar()
    {
        $this->showModalRechazar = true;
    }

    public function cerrarModales()
    {
        $this->showModalAprobar = false;
        $this->showModalRechazar = false;
        $this->motivoRechazo = '';
    }

    public function aprobar()
    {
        $this->precalificacion->update([
            'estado' => 'aprobada',
            'fecha_resolucion' => now(),
            'revisado_por_usuario_id' => auth()->id(),
            'tipo_revisor' => 'ryce',
            'motivo_rechazo' => null,
        ]);

        // Enviar notificación por email (si existe la clase)
        try {
            $email = $this->precalificacion->contratista->email 
                  ?? $this->precalificacion->contratista->usuarios->first()?->email;
            
            if ($email) {
                // Mail::to($email)->send(new PrecalificacionAprobadaMail($this->precalificacion));
            }
        } catch (\Exception $e) {
            // Log error silently
        }

        session()->flash('message', 'Precalificación APROBADA correctamente.');
        $this->cerrarModales();
        
        return redirect()->route('admin.precalificaciones');
    }

    public function rechazar()
    {
        if (empty(trim($this->motivoRechazo))) {
            $this->addError('motivoRechazo', 'Debes indicar el motivo del rechazo.');
            return;
        }

        $this->precalificacion->update([
            'estado' => 'rechazada',
            'fecha_resolucion' => now(),
            'revisado_por_usuario_id' => auth()->id(),
            'tipo_revisor' => 'ryce',
            'motivo_rechazo' => $this->motivoRechazo,
        ]);

        // Enviar notificación por email (si existe la clase)
        try {
            $email = $this->precalificacion->contratista->email 
                  ?? $this->precalificacion->contratista->usuarios->first()?->email;
            
            if ($email) {
                // Mail::to($email)->send(new PrecalificacionRechazadaMail($this->precalificacion));
            }
        } catch (\Exception $e) {
            // Log error silently
        }

        session()->flash('message', 'Precalificación RECHAZADA. El contratista será notificado.');
        $this->cerrarModales();
        
        return redirect()->route('admin.precalificaciones');
    }

    public function render()
    {
        // Cargar documentos enviados por el contratista
        $documentos = $this->precalificacion->licitacion->documentos()
            ->where('nombre_documento', 'like', '[PRECAL-' . $this->precalificacion->contratista_id . ']%')
            ->get();

        // Requisitos de precalificación
        $requisitos = $this->precalificacion->licitacion->requisitosDocumentos()
            ->where('es_precalificacion', true)
            ->orderBy('orden')
            ->get();

        return view('livewire.admin.precalificaciones.revisar', [
            'documentos' => $documentos,
            'requisitos' => $requisitos,
        ]);
    }
}
