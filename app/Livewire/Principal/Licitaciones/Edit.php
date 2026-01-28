<?php

namespace App\Livewire\Principal\Licitaciones;

use App\Models\CategoriaLicitacion;
use App\Models\Licitacion;
use App\Models\DocumentoLicitacion;
use App\Models\RequisitoDocumentoLicitacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class Edit extends Component
{
    use WithFileUploads;

    public Licitacion $licitacion;
    
    // Paso actual del wizard
    public $currentStep = 1;
    public $totalSteps = 3;

    // Paso 1: Información Básica
    public $titulo = '';
    public $descripcion = '';
    public $tipo_licitacion = 'publica';
    public $presupuesto_referencial = '';
    public $moneda = 'CLP';
    public $selectedCategorias = [];

    // Paso 2: Fechas
    public $fecha_inicio_consultas = '';
    public $fecha_cierre_consultas = '';
    public $fecha_cierre_recepcion_ofertas = '';
    public $fecha_apertura_ofertas = '';
    public $fecha_adjudicacion_estimada = '';

    // Paso 2b: Visita a Terreno
    public $requiere_visita_terreno = false;
    public $fecha_visita_terreno = '';
    public $contacto_visita_terreno = '';
    public $email_contacto_visita = '';
    public $telefono_contacto_visita = '';
    public $lugar_visita_terreno = '';
    public $visita_terreno_obligatoria = false;

    // Paso 2c: Precalificación
    public $requiere_precalificacion = false;
    public $fecha_inicio_precalificacion = '';
    public $fecha_fin_precalificacion = '';
    public $requiere_entrevista = false;
    public $fecha_entrevista = '';
    public $lugar_entrevista = '';
    public $notas_precalificacion = '';

    // Paso 3: Documentos y Requisitos
    public $documentosExistentes = [];
    public $nuevosDocumentosData = []; // [{nombre, archivo, archivoNombre}]
    public $nuevoDocNombre = '';
    public $nuevoDocArchivo = null;
    public $documentosAEliminar = [];
    public $requisitos = [];
    public $nuevoRequisito = '';

    // Observaciones de RyCE
    public $observacionesRyCE = '';

    public function mount(Licitacion $licitacion)
    {
        $user = auth()->user();

        // Verificar que la licitación pertenezca a la empresa del usuario
        if ($licitacion->principal_id !== $user->empresa_principal_id) {
            abort(403, 'No tienes permiso para editar esta licitación.');
        }

        // Solo se puede editar si está en borrador u observada
        if (!in_array($licitacion->estado, ['borrador', 'observada_por_ryce'])) {
            session()->flash('error', 'Esta licitación no puede ser editada en su estado actual.');
            return redirect()->route('principal.licitaciones.show', $licitacion);
        }

        $this->licitacion = $licitacion->load(['categorias', 'documentos', 'requisitosDocumentos']);
        
        // Cargar datos existentes
        $this->titulo = $licitacion->titulo;
        $this->descripcion = $licitacion->descripcion_corta;
        $this->tipo_licitacion = $licitacion->tipo_licitacion;
        $this->presupuesto_referencial = $licitacion->presupuesto_referencial;
        $this->moneda = $licitacion->moneda_presupuesto ?? 'CLP';
        $this->selectedCategorias = $licitacion->categorias->pluck('id')->toArray();

        // Fechas (datetime-local)
        $this->fecha_inicio_consultas = $licitacion->fecha_inicio_consultas?->format('Y-m-d\TH:i');
        $this->fecha_cierre_consultas = $licitacion->fecha_cierre_consultas?->format('Y-m-d\TH:i');
        $this->fecha_cierre_recepcion_ofertas = $licitacion->fecha_cierre_recepcion_ofertas?->format('Y-m-d\TH:i');
        $this->fecha_adjudicacion_estimada = $licitacion->fecha_adjudicacion_estimada?->format('Y-m-d\TH:i');

        // Visita a terreno
        $this->requiere_visita_terreno = $licitacion->requiere_visita_terreno ?? false;
        $this->fecha_visita_terreno = $licitacion->fecha_visita_terreno?->format('Y-m-d\TH:i');
        $this->contacto_visita_terreno = $licitacion->contacto_visita_terreno ?? '';
        $this->email_contacto_visita = $licitacion->email_contacto_visita ?? '';
        $this->telefono_contacto_visita = $licitacion->telefono_contacto_visita ?? '';
        $this->lugar_visita_terreno = $licitacion->lugar_visita_terreno ?? '';
        $this->visita_terreno_obligatoria = $licitacion->visita_terreno_obligatoria ?? false;

        // Precalificación
        $this->requiere_precalificacion = $licitacion->requiere_precalificacion ?? false;
        $this->fecha_inicio_precalificacion = $licitacion->fecha_inicio_precalificacion?->format('Y-m-d\TH:i');
        $this->fecha_fin_precalificacion = $licitacion->fecha_fin_precalificacion?->format('Y-m-d\TH:i');
        $this->requiere_entrevista = $licitacion->requiere_entrevista ?? false;
        $this->fecha_entrevista = $licitacion->fecha_entrevista?->format('Y-m-d\TH:i');
        $this->lugar_entrevista = $licitacion->lugar_entrevista ?? '';
        $this->notas_precalificacion = $licitacion->notas_precalificacion ?? '';

        // Documentos existentes
        $this->documentosExistentes = $licitacion->documentos->map(function ($doc) {
            return [
                'id' => $doc->id,
                'nombre' => $doc->nombre_documento,
                'tipo' => $doc->tipo_documento,
                'ruta' => $doc->ruta_archivo,
            ];
        })->toArray();

        // Requisitos existentes
        $this->requisitos = $licitacion->requisitosDocumentos->map(function ($req) {
            return [
                'id' => $req->id,
                'nombre' => $req->nombre_requisito,
                'obligatorio' => $req->es_obligatorio,
            ];
        })->toArray();

        // Observaciones de RyCE
        $this->observacionesRyCE = $licitacion->comentarios_revision_ryce ?? '';
    }

    protected function rules()
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:20',
            'tipo_licitacion' => 'required|in:publica,privada',
            'presupuesto_referencial' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:CLP,UF,USD',
            'selectedCategorias' => 'required|array|min:1',
            'selectedCategorias.*' => 'exists:categorias_licitaciones,id',
            'fecha_inicio_consultas' => 'required|date',
            'fecha_cierre_consultas' => 'required|date|after:fecha_inicio_consultas',
            'fecha_cierre_recepcion_ofertas' => 'required|date|after:fecha_cierre_consultas',
            'fecha_adjudicacion_estimada' => 'nullable|date|after:fecha_cierre_recepcion_ofertas',
            'nuevosDocumentosData.*.nombre' => 'required|string|max:255',
            'nuevoDocArchivo' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,zip,jpg,png',
        ];
    }

    protected $messages = [
        'titulo.required' => 'El título es obligatorio.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.min' => 'La descripción debe tener al menos 20 caracteres.',
        'selectedCategorias.required' => 'Debe seleccionar al menos una categoría.',
        'selectedCategorias.min' => 'Debe seleccionar al menos una categoría.',
        'fecha_cierre_consultas.after' => 'Debe ser posterior al inicio de consultas.',
        'fecha_cierre_recepcion_ofertas.after' => 'Debe ser posterior al cierre de consultas.',
        'nuevosDocumentos.*.max' => 'Cada documento no puede superar los 10MB.',
    ];

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validateOnly('titulo');
            $this->validateOnly('descripcion');
            $this->validateOnly('tipo_licitacion');
            $this->validateOnly('selectedCategorias');
        }

        if ($this->currentStep === 2) {
            $this->validateOnly('fecha_inicio_consultas');
            $this->validateOnly('fecha_cierre_consultas');
            $this->validateOnly('fecha_cierre_recepcion_ofertas');
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }

    public function addRequisito()
    {
        if (trim($this->nuevoRequisito) !== '') {
            $this->requisitos[] = [
                'id' => null,
                'nombre' => $this->nuevoRequisito,
                'obligatorio' => true,
            ];
            $this->nuevoRequisito = '';
        }
    }

    public function removeRequisito($index)
    {
        unset($this->requisitos[$index]);
        $this->requisitos = array_values($this->requisitos);
    }

    public function toggleRequisito($index)
    {
        if (isset($this->requisitos[$index])) {
            $this->requisitos[$index]['obligatorio'] = !$this->requisitos[$index]['obligatorio'];
        }
    }

    public function marcarDocumentoEliminar($index)
    {
        if (isset($this->documentosExistentes[$index])) {
            $this->documentosAEliminar[] = $this->documentosExistentes[$index]['id'];
            unset($this->documentosExistentes[$index]);
            $this->documentosExistentes = array_values($this->documentosExistentes);
        }
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

        $this->nuevosDocumentosData[] = $docData;
        $this->nuevoDocNombre = '';
        $this->nuevoDocArchivo = null;
    }

    public function removeNuevoDocumento($index)
    {
        unset($this->nuevosDocumentosData[$index]);
        $this->nuevosDocumentosData = array_values($this->nuevosDocumentosData);
    }

    public function guardarBorrador()
    {
        $this->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|min:20',
        ]);

        $this->actualizarLicitacion('borrador');
        
        session()->flash('message', 'Licitación guardada como borrador.');
        return redirect()->route('principal.licitaciones');
    }

    public function enviarParaRevision()
    {
        $this->validate();

        $this->actualizarLicitacion('lista_para_publicar');
        
        session()->flash('message', 'Licitación enviada para revisión de RyCE.');
        return redirect()->route('principal.licitaciones');
    }

    private function actualizarLicitacion($estado)
    {
        $user = auth()->user();

        // Actualizar licitación
        $this->licitacion->update([
            'titulo' => $this->titulo,
            'descripcion_corta' => $this->descripcion,
            'tipo_licitacion' => $this->tipo_licitacion,
            'presupuesto_referencial' => $this->presupuesto_referencial ?: null,
            'moneda_presupuesto' => $this->moneda,
            'fecha_inicio_consultas' => $this->fecha_inicio_consultas,
            'fecha_cierre_consultas' => $this->fecha_cierre_consultas,
            'fecha_cierre_recepcion_ofertas' => $this->fecha_cierre_recepcion_ofertas,
            'fecha_adjudicacion_estimada' => $this->fecha_adjudicacion_estimada ?: null,
            // Visita a terreno
            'requiere_visita_terreno' => $this->requiere_visita_terreno,
            'fecha_visita_terreno' => $this->requiere_visita_terreno ? $this->fecha_visita_terreno : null,
            'contacto_visita_terreno' => $this->requiere_visita_terreno ? $this->contacto_visita_terreno : null,
            'email_contacto_visita' => $this->requiere_visita_terreno ? $this->email_contacto_visita : null,
            'telefono_contacto_visita' => $this->requiere_visita_terreno ? $this->telefono_contacto_visita : null,
            'lugar_visita_terreno' => $this->requiere_visita_terreno ? $this->lugar_visita_terreno : null,
            'visita_terreno_obligatoria' => $this->requiere_visita_terreno ? $this->visita_terreno_obligatoria : false,
            // Precalificación
            'requiere_precalificacion' => $this->requiere_precalificacion,
            'fecha_inicio_precalificacion' => $this->requiere_precalificacion ? $this->fecha_inicio_precalificacion : null,
            'fecha_fin_precalificacion' => $this->requiere_precalificacion ? $this->fecha_fin_precalificacion : null,
            'requiere_entrevista' => $this->requiere_precalificacion ? $this->requiere_entrevista : false,
            'fecha_entrevista' => ($this->requiere_precalificacion && $this->requiere_entrevista) ? $this->fecha_entrevista : null,
            'lugar_entrevista' => ($this->requiere_precalificacion && $this->requiere_entrevista) ? $this->lugar_entrevista : null,
            'notas_precalificacion' => $this->requiere_precalificacion ? $this->notas_precalificacion : null,
            'estado' => $estado,
            'comentarios_revision_ryce' => null, // Limpiar observaciones al reenviar
        ]);

        // Actualizar categorías
        $this->licitacion->categorias()->sync($this->selectedCategorias);

        // Eliminar documentos marcados
        foreach ($this->documentosAEliminar as $docId) {
            $doc = DocumentoLicitacion::find($docId);
            if ($doc) {
                Storage::disk('public')->delete($doc->ruta_archivo);
                $doc->delete();
            }
        }

        // Guardar nuevos documentos
        foreach ($this->nuevosDocumentosData as $docData) {
            $path = null;
            
            if ($docData['archivo']) {
                $path = $docData['archivo']->store('licitaciones/' . $this->licitacion->id . '/documentos', 'public');
            }
            
            DocumentoLicitacion::create([
                'licitacion_id' => $this->licitacion->id,
                'tipo_documento' => 'bases',
                'nombre_documento' => $docData['nombre'],
                'ruta_archivo' => $path,
                'subido_por_usuario_id' => $user->id,
            ]);
        }

        // Sincronizar requisitos
        $existingIds = collect($this->requisitos)->pluck('id')->filter()->toArray();
        RequisitoDocumentoLicitacion::where('licitacion_id', $this->licitacion->id)
            ->whereNotIn('id', $existingIds)
            ->delete();

        $orden = 1;
        foreach ($this->requisitos as $requisito) {
            if ($requisito['id']) {
                RequisitoDocumentoLicitacion::where('id', $requisito['id'])->update([
                    'nombre_requisito' => $requisito['nombre'],
                    'es_obligatorio' => $requisito['obligatorio'],
                    'orden' => $orden++,
                ]);
            } else {
                RequisitoDocumentoLicitacion::create([
                    'licitacion_id' => $this->licitacion->id,
                    'nombre_requisito' => $requisito['nombre'],
                    'descripcion_requisito' => null,
                    'es_obligatorio' => $requisito['obligatorio'],
                    'orden' => $orden++,
                ]);
            }
        }
    }

    public function render()
    {
        $categorias = CategoriaLicitacion::orderBy('nombre_categoria')->get();

        return view('livewire.principal.licitaciones.edit', [
            'categorias' => $categorias,
        ]);
    }
}
