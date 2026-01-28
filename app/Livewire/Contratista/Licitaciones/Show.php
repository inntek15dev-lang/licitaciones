<?php

namespace App\Livewire\Contratista\Licitaciones;

use App\Models\Licitacion;
use App\Models\Oferta;
use App\Models\PrecalificacionContratista;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Licitacion $licitacion;
    public $yaPostulo = false;
    public $miOferta = null;
    
    // Precalificación
    public $requierePrecalificacion = false;
    public $precalificacion = null;
    public $estadoPrecalificacion = null;

    public function mount(Licitacion $licitacion)
    {
        // Verificar que la licitación esté publicada
        if (!in_array($licitacion->estado, ['publicada', 'cerrada_consultas', 'cerrada_ofertas', 'en_evaluacion', 'adjudicada'])) {
            abort(404);
        }

        $this->licitacion = $licitacion->load([
            'principal',
            'categorias',
            'documentos',
            'requisitosDocumentos',
            'consultasRespuestas' => function ($query) {
                $query->whereNotNull('texto_respuesta')
                      ->orderBy('fecha_respuesta', 'desc');
            }
        ]);

        $user = auth()->user();
        
        // Verificar si el contratista ya postuló
        if ($user && $user->empresa_contratista_id) {
            $this->miOferta = Oferta::where('licitacion_id', $licitacion->id)
                ->where('contratista_id', $user->empresa_contratista_id)
                ->first();
            $this->yaPostulo = $this->miOferta !== null;

            // Verificar precalificación
            $this->requierePrecalificacion = $licitacion->requiere_precalificacion ?? false;
            
            if ($this->requierePrecalificacion) {
                $this->precalificacion = PrecalificacionContratista::where('licitacion_id', $licitacion->id)
                    ->where('contratista_id', $user->empresa_contratista_id)
                    ->first();
                    
                $this->estadoPrecalificacion = $this->precalificacion?->estado;
            }
        }
    }

    public function puedePostular()
    {
        // Solo puede postular si:
        // 1. La licitación está publicada
        // 2. Está dentro del periodo de recepción de ofertas
        // 3. No ha postulado aún
        // 4. Si requiere precalificación, debe estar aprobada
        
        if ($this->licitacion->estado !== 'publicada') {
            return false;
        }

        $now = now();
        $fechaInicio = $this->licitacion->fecha_inicio_recepcion_ofertas ?? $this->licitacion->fecha_publicacion;
        $fechaCierre = $this->licitacion->fecha_cierre_recepcion_ofertas;

        if ($fechaCierre && $now > $fechaCierre) {
            return false;
        }

        // Verificar precalificación
        if ($this->requierePrecalificacion) {
            if (!$this->precalificacion || $this->estadoPrecalificacion !== 'aprobada') {
                return false;
            }
        }

        return !$this->yaPostulo;
    }

    public function render()
    {
        return view('livewire.contratista.licitaciones.show', [
            'puedePostular' => $this->puedePostular(),
            'requierePrecalificacion' => $this->requierePrecalificacion,
            'precalificacion' => $this->precalificacion,
            'estadoPrecalificacion' => $this->estadoPrecalificacion,
        ]);
    }
}

