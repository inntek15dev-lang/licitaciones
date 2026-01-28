<?php

namespace App\Livewire\Contratista\Licitaciones;

use App\Models\Licitacion;
use App\Models\PrecalificacionContratista;
use App\Models\DocumentoLicitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class SolicitudPrecalificacion extends Component
{
    use WithFileUploads;

    public Licitacion $licitacion;
    public ?PrecalificacionContratista $precalificacion = null;

    // Para nueva solicitud
    public $comentarios = '';
    public $documentos = [];
    public $documentosRequisitos = []; // Documentos por cada requisito
    public $nuevoDocNombre = '';
    public $nuevoDocArchivo = null;

    // Para rectificación
    public $comentariosRectificacion = '';
    public $showRectificacionForm = false;

    public function mount(Licitacion $licitacion)
    {
        $user = auth()->user();
        $contratista = $user->empresaContratista;

        // Verificar que existe empresa contratista
        if (!$contratista) {
            abort(403, 'No tienes una empresa contratista asociada.');
        }

        // Verificar que la licitación requiere precalificación
        if (!$licitacion->requiere_precalificacion) {
            return redirect()->route('contratista.licitaciones.show', $licitacion->id)
                ->with('message', 'Esta licitación no requiere precalificación.');
        }

        $this->licitacion = $licitacion;
        
        // Buscar precalificación existente
        $this->precalificacion = $licitacion->precalificaciones()
            ->where('contratista_id', $contratista->id)
            ->first();
    }

    public function addDocumento()
    {
        if (trim($this->nuevoDocNombre) === '') {
            $this->addError('nuevoDocNombre', 'El nombre del documento es obligatorio.');
            return;
        }

        $docData = [
            'nombre' => $this->nuevoDocNombre,
            'archivo' => null,
            'archivoNombre' => null,
        ];

        if ($this->nuevoDocArchivo) {
            $docData['archivo'] = $this->nuevoDocArchivo;
            $docData['archivoNombre'] = $this->nuevoDocArchivo->getClientOriginalName();
        }

        $this->documentos[] = $docData;
        $this->nuevoDocNombre = '';
        $this->nuevoDocArchivo = null;
    }

    public function removeDocumento($index)
    {
        unset($this->documentos[$index]);
        $this->documentos = array_values($this->documentos);
    }

    public function enviarSolicitud()
    {
        $user = auth()->user();
        $contratista = $user->empresaContratista;

        // Verificar que no exista ya una precalificación
        if ($this->precalificacion) {
            session()->flash('error', 'Ya tienes una solicitud de precalificación para esta licitación.');
            return;
        }

        // Crear la solicitud
        $this->precalificacion = PrecalificacionContratista::create([
            'licitacion_id' => $this->licitacion->id,
            'contratista_id' => $contratista->id,
            'estado' => 'pendiente',
            'fecha_solicitud' => now(),
            'comentarios_contratista' => $this->comentarios,
        ]);

        // Guardar documentos de requisitos
        foreach ($this->documentosRequisitos as $requisitoId => $archivo) {
            if ($archivo) {
                $requisito = $this->licitacion->requisitosDocumentos()->find($requisitoId);
                $path = $archivo->store(
                    'precalificaciones/' . $this->precalificacion->id . '/documentos', 
                    'public'
                );
                
                DocumentoLicitacion::create([
                    'licitacion_id' => $this->licitacion->id,
                    'tipo_documento' => 'otro',
                    'es_precalificacion' => true,
                    'nombre_documento' => '[PRECAL-' . $contratista->id . '] ' . ($requisito->nombre_requisito ?? 'Documento'),
                    'ruta_archivo' => $path,
                    'subido_por_usuario_id' => $user->id,
                ]);
            }
        }

        // Guardar documentos adicionales
        foreach ($this->documentos as $docData) {
            $path = null;
            
            if ($docData['archivo']) {
                $path = $docData['archivo']->store(
                    'precalificaciones/' . $this->precalificacion->id . '/documentos', 
                    'public'
                );
            }
            
            DocumentoLicitacion::create([
                'licitacion_id' => $this->licitacion->id,
                'tipo_documento' => 'otro',
                'es_precalificacion' => true,
                'nombre_documento' => '[PRECAL-' . $contratista->id . '] ' . $docData['nombre'],
                'ruta_archivo' => $path,
                'subido_por_usuario_id' => $user->id,
            ]);
        }

        session()->flash('message', 'Solicitud de precalificación enviada correctamente. Serás notificado cuando sea revisada.');
        
        // Limpiar formulario
        $this->comentarios = '';
        $this->documentos = [];
        $this->documentosRequisitos = [];
    }

    public function mostrarFormRectificacion()
    {
        $this->showRectificacionForm = true;
    }

    public function enviarRectificacion()
    {
        if (!$this->precalificacion || !$this->precalificacion->estaRechazada()) {
            return;
        }

        $this->precalificacion->update([
            'estado' => 'rectificando',
            'comentarios_rectificacion' => $this->comentariosRectificacion,
            'fecha_resolucion' => null, // Reset para nueva revisión
        ]);

        session()->flash('message', 'Rectificación enviada. Será revisada nuevamente.');
        
        $this->showRectificacionForm = false;
        $this->comentariosRectificacion = '';
    }

    public function render()
    {
        // Obtener requisitos de precalificación
        $requisitos = $this->licitacion->requisitosDocumentos()
            ->where('es_precalificacion', true)
            ->orderBy('orden')
            ->get();

        // Obtener documentos de precalificación de la licitación
        $documentosLicitacion = $this->licitacion->documentos()
            ->where('es_precalificacion', true)
            ->get();

        return view('livewire.contratista.licitaciones.solicitud-precalificacion', [
            'requisitos' => $requisitos,
            'documentosLicitacion' => $documentosLicitacion,
        ]);
    }
}
