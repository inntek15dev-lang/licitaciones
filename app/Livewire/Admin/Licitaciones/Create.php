<?php

namespace App\Livewire\Admin\Licitaciones;

use App\Models\CatalogoRequisitoPrecalificacion;
use App\Models\CategoriaLicitacion;
use App\Models\EmpresaPrincipal;
use App\Models\FormularioPrecalificacion;
use App\Models\Licitacion;
use App\Models\DocumentoLicitacion;
use App\Models\RequisitoDocumentoLicitacion;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    // Paso actual del wizard
    public $currentStep = 1;
    public $totalSteps = 3;

    // Empresa Principal (Admin debe seleccionar)
    public $principal_id = '';

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
    public $responsable_precalificacion = 'ryce';
    public $fecha_inicio_precalificacion = '';
    public $fecha_fin_precalificacion = '';
    public $requiere_entrevista = false;
    public $fecha_entrevista = '';
    public $lugar_entrevista = '';
    public $notas_precalificacion = '';

    // Precalificación: Documentos y Requisitos
    public $documentosPrecalificacion = [];
    public $nuevoDocPrecalNombre = '';
    public $nuevoDocPrecalArchivo = null;
    public $requisitosPrecalificacion = [];
    public $nuevoRequisitoPrecal = '';
    public $selectedRequisitoCatalogo = '';
    
    // Selección de Formulario de Precalificación
    public $formulariosPrecalificacion = [];
    public $selectedFormulario = '';
    public $formularioLoaded = false;

    // Paso 3: Documentos y Requisitos
    public $documentosData = [];
    public $nuevoDocNombre = '';
    public $nuevoDocArchivo = null;
    public $requisitos = [];
    public $nuevoRequisito = '';

    public function mount()
    {
        $this->fecha_inicio_consultas = now()->addDay()->format('Y-m-d\TH:i');
        $this->fecha_cierre_consultas = now()->addDays(7)->format('Y-m-d\TH:i');
        $this->fecha_cierre_recepcion_ofertas = now()->addDays(14)->format('Y-m-d\TH:i');
        $this->fecha_apertura_ofertas = now()->addDays(15)->format('Y-m-d\TH:i');
        $this->fecha_adjudicacion_estimada = now()->addDays(30)->format('Y-m-d\TH:i');
    }

    /**
     * Cuando cambia el principal seleccionado, carga sus formularios de precalificación.
     */
    public function updatedPrincipalId($value)
    {
        $this->selectedFormulario = '';
        $this->formularioLoaded = false;
        
        if ($value) {
            $this->formulariosPrecalificacion = FormularioPrecalificacion::activos()
                ->where('empresa_principal_id', $value)
                ->with('requisitos')
                ->orderBy('nombre')
                ->get()
                ->toArray();
        } else {
            $this->formulariosPrecalificacion = [];
        }
    }

    /**
     * Cuando se selecciona un formulario, carga sus requisitos.
     */
    public function updatedSelectedFormulario($value)
    {
        if ($value) {
            $formulario = FormularioPrecalificacion::with('requisitos')->find($value);
            
            if ($formulario) {
                // Limpiar requisitos actuales y cargar los del formulario
                $this->requisitosPrecalificacion = [];
                
                foreach ($formulario->requisitos as $requisito) {
                    $this->requisitosPrecalificacion[] = [
                        'nombre' => $requisito->nombre_requisito,
                        'criterio' => $requisito->criterio_cumplimiento,
                        'obligatorio' => $requisito->pivot->obligatorio,
                        'from_catalog' => true,
                    ];
                }
                
                $this->formularioLoaded = true;
            }
        } else {
            $this->formularioLoaded = false;
        }
    }

    protected function rules()
    {
        return [
            'principal_id' => 'required|exists:empresas_principales,id',
            'titulo' => 'required|string|min:10|max:255',
            'descripcion' => 'required|string|min:20',
            'tipo_licitacion' => 'required|in:publica,privada',
            'presupuesto_referencial' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:CLP,USD,UF',
            'selectedCategorias' => 'required|array|min:1',
            'fecha_inicio_consultas' => 'required|date',
            'fecha_cierre_consultas' => 'required|date|after:fecha_inicio_consultas',
            'fecha_cierre_recepcion_ofertas' => 'required|date|after:fecha_cierre_consultas',
            'fecha_adjudicacion_estimada' => 'nullable|date|after:fecha_cierre_recepcion_ofertas',
        ];
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'principal_id' => 'required|exists:empresas_principales,id',
                'titulo' => 'required|string|min:10|max:255',
                'descripcion' => 'required|string|min:20',
                'tipo_licitacion' => 'required|in:publica,privada',
                'selectedCategorias' => 'required|array|min:1',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'fecha_inicio_consultas' => 'required|date',
                'fecha_cierre_consultas' => 'required|date|after:fecha_inicio_consultas',
                'fecha_cierre_recepcion_ofertas' => 'required|date|after:fecha_cierre_consultas',
            ]);
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
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

        $this->documentosData[] = $docData;
        $this->nuevoDocNombre = '';
        $this->nuevoDocArchivo = null;
    }

    public function removeDocumento($index)
    {
        unset($this->documentosData[$index]);
        $this->documentosData = array_values($this->documentosData);
    }

    public function addRequisito()
    {
        if (trim($this->nuevoRequisito) !== '') {
            $this->requisitos[] = [
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

    public function toggleRequisitoObligatorio($index)
    {
        $this->requisitos[$index]['obligatorio'] = !$this->requisitos[$index]['obligatorio'];
    }

    // Métodos para Precalificación
    public function addDocumentoPrecal()
    {
        if (trim($this->nuevoDocPrecalNombre) === '') {
            $this->addError('nuevoDocPrecalNombre', 'El nombre del documento es obligatorio.');
            return;
        }

        $docData = [
            'nombre' => $this->nuevoDocPrecalNombre,
            'archivo' => null,
            'archivoNombre' => null,
        ];

        if ($this->nuevoDocPrecalArchivo) {
            $docData['archivo'] = $this->nuevoDocPrecalArchivo;
            $docData['archivoNombre'] = $this->nuevoDocPrecalArchivo->getClientOriginalName();
        }

        $this->documentosPrecalificacion[] = $docData;
        $this->nuevoDocPrecalNombre = '';
        $this->nuevoDocPrecalArchivo = null;
    }

    public function removeDocumentoPrecal($index)
    {
        unset($this->documentosPrecalificacion[$index]);
        $this->documentosPrecalificacion = array_values($this->documentosPrecalificacion);
    }

    public function addRequisitoPrecal()
    {
        if (trim($this->nuevoRequisitoPrecal) !== '') {
            $this->requisitosPrecalificacion[] = [
                'nombre' => $this->nuevoRequisitoPrecal,
                'obligatorio' => true,
            ];
            $this->nuevoRequisitoPrecal = '';
        }
    }

    public function removeRequisitoPrecal($index)
    {
        unset($this->requisitosPrecalificacion[$index]);
        $this->requisitosPrecalificacion = array_values($this->requisitosPrecalificacion);
    }

    public function toggleRequisitoPrecalObligatorio($index)
    {
        $this->requisitosPrecalificacion[$index]['obligatorio'] = !$this->requisitosPrecalificacion[$index]['obligatorio'];
    }

    public function addRequisitoDesdeCatalogo()
    {
        if (empty($this->selectedRequisitoCatalogo)) {
            return;
        }

        $catalogo = CatalogoRequisitoPrecalificacion::find($this->selectedRequisitoCatalogo);
        if ($catalogo) {
            // Verificar que no esté ya agregado
            foreach ($this->requisitosPrecalificacion as $req) {
                if ($req['nombre'] === $catalogo->nombre_requisito) {
                    $this->addError('selectedRequisitoCatalogo', 'Este requisito ya fue agregado.');
                    return;
                }
            }

            $this->requisitosPrecalificacion[] = [
                'nombre' => $catalogo->nombre_requisito,
                'criterio' => $catalogo->criterio_cumplimiento,
                'obligatorio' => true,
                'from_catalog' => true,
            ];
            $this->selectedRequisitoCatalogo = '';
            $this->resetErrorBag('selectedRequisitoCatalogo');
        }
    }

    public function guardarBorrador()
    {
        $this->validate([
            'principal_id' => 'required|exists:empresas_principales,id',
            'titulo' => 'required|string|min:5',
        ]);
        
        $this->crearLicitacion('borrador');
        
        session()->flash('message', 'Licitación guardada como borrador.');
        return redirect()->route('admin.licitaciones');
    }

    public function enviarRevision()
    {
        $this->validate();
        
        // Admin crea directamente publicada o lista para publicar
        $this->crearLicitacion('publicada');
        
        session()->flash('message', 'Licitación creada y publicada correctamente.');
        return redirect()->route('admin.licitaciones');
    }

    private function crearLicitacion($estado)
    {
        $user = auth()->user();

        $licitacion = Licitacion::create([
            'codigo_licitacion' => 'LIC-' . strtoupper(Str::random(8)),
            'titulo' => $this->titulo,
            'descripcion_corta' => $this->descripcion,
            'principal_id' => $this->principal_id,
            'usuario_creador_id' => $user->id,
            'tipo_licitacion' => $this->tipo_licitacion,
            'estado' => $estado,
            'presupuesto_referencial' => $this->presupuesto_referencial ?: null,
            'moneda_presupuesto' => $this->moneda,
            'fecha_inicio_consultas' => $this->fecha_inicio_consultas,
            'fecha_cierre_consultas' => $this->fecha_cierre_consultas,
            'fecha_cierre_recepcion_ofertas' => $this->fecha_cierre_recepcion_ofertas,
            'fecha_adjudicacion_estimada' => $this->fecha_adjudicacion_estimada ?: null,
            'fecha_publicacion' => $estado === 'publicada' ? now() : null,
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
            'responsable_precalificacion' => $this->requiere_precalificacion ? $this->responsable_precalificacion : 'ryce',
            'formulario_precalificacion_id' => ($this->requiere_precalificacion && $this->selectedFormulario) ? $this->selectedFormulario : null,
            'fecha_inicio_precalificacion' => $this->requiere_precalificacion ? $this->fecha_inicio_precalificacion : null,
            'fecha_fin_precalificacion' => $this->requiere_precalificacion ? $this->fecha_fin_precalificacion : null,
            'requiere_entrevista' => $this->requiere_precalificacion ? $this->requiere_entrevista : false,
            'fecha_entrevista' => ($this->requiere_precalificacion && $this->requiere_entrevista) ? $this->fecha_entrevista : null,
            'lugar_entrevista' => ($this->requiere_precalificacion && $this->requiere_entrevista) ? $this->lugar_entrevista : null,
            'notas_precalificacion' => $this->requiere_precalificacion ? $this->notas_precalificacion : null,
        ]);

        // Asociar categorías
        $licitacion->categorias()->sync($this->selectedCategorias);

        // Guardar documentos
        foreach ($this->documentosData as $docData) {
            $path = null;
            
            if ($docData['archivo']) {
                $path = $docData['archivo']->store('licitaciones/' . $licitacion->id . '/documentos', 'public');
            }
            
            DocumentoLicitacion::create([
                'licitacion_id' => $licitacion->id,
                'tipo_documento' => 'bases',
                'nombre_documento' => $docData['nombre'],
                'ruta_archivo' => $path,
                'subido_por_usuario_id' => $user->id,
            ]);
        }

        // Guardar requisitos
        $orden = 1;
        foreach ($this->requisitos as $requisito) {
            RequisitoDocumentoLicitacion::create([
                'licitacion_id' => $licitacion->id,
                'nombre_requisito' => $requisito['nombre'],
                'descripcion_requisito' => null,
                'es_obligatorio' => $requisito['obligatorio'],
                'es_precalificacion' => false,
                'orden' => $orden++,
            ]);
        }

        // Guardar documentos de precalificación
        if ($this->requiere_precalificacion) {
            foreach ($this->documentosPrecalificacion as $docData) {
                $path = null;
                
                if ($docData['archivo']) {
                    $path = $docData['archivo']->store('licitaciones/' . $licitacion->id . '/precalificacion', 'public');
                }
                
                DocumentoLicitacion::create([
                    'licitacion_id' => $licitacion->id,
                    'tipo_documento' => 'otro',
                    'es_precalificacion' => true,
                    'nombre_documento' => $docData['nombre'],
                    'ruta_archivo' => $path,
                    'subido_por_usuario_id' => $user->id,
                ]);
            }

            // Guardar requisitos de precalificación
            $ordenPrecal = 1;
            foreach ($this->requisitosPrecalificacion as $requisito) {
                RequisitoDocumentoLicitacion::create([
                    'licitacion_id' => $licitacion->id,
                    'nombre_requisito' => $requisito['nombre'],
                    'descripcion_requisito' => $requisito['criterio'] ?? null,
                    'es_obligatorio' => $requisito['obligatorio'],
                    'es_precalificacion' => true,
                    'orden' => $ordenPrecal++,
                ]);
            }
        }

        return $licitacion;
    }

    public function render()
    {
        $categorias = CategoriaLicitacion::orderBy('nombre_categoria')->get();
        $empresas = EmpresaPrincipal::orderBy('razon_social')->get();
        $catalogoRequisitos = CatalogoRequisitoPrecalificacion::activos()->orderBy('nombre_requisito')->get();

        return view('livewire.admin.licitaciones.create', [
            'categorias' => $categorias,
            'empresas' => $empresas,
            'catalogoRequisitos' => $catalogoRequisitos,
        ]);
    }
}
