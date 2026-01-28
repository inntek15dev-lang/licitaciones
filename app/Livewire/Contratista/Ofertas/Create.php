<?php

namespace App\Livewire\Contratista\Ofertas;

use App\Models\Licitacion;
use App\Models\Oferta;
use App\Models\DocumentoOferta;
use App\Models\Notificacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public Licitacion $licitacion;
    public $montoOferta = '';
    public $monedaOferta = 'CLP';
    public $validezDias = 30;
    public $comentarios = '';
    
    // Documentos subidos
    public $documentosArchivos = [];
    public $documentosInfo = [];

    public function mount(Licitacion $licitacion)
    {
        $user = auth()->user();
        
        // Verificar que el usuario es contratista
        if (!$user->empresa_contratista_id) {
            abort(403, 'Solo usuarios contratistas pueden postular.');
        }

        // Verificar que la licitación acepta ofertas
        if (!$licitacion->aceptaOfertas()) {
            session()->flash('error', 'Esta licitación no está en período de recepción de ofertas.');
            return redirect()->route('contratista.licitaciones.show', $licitacion);
        }

        // Verificar que no haya postulado ya
        $yaPostulo = Oferta::where('licitacion_id', $licitacion->id)
            ->where('contratista_id', $user->empresa_contratista_id)
            ->exists();

        if ($yaPostulo) {
            session()->flash('error', 'Ya has postulado a esta licitación.');
            return redirect()->route('contratista.licitaciones.show', $licitacion);
        }

        $this->licitacion = $licitacion->load(['requisitosDocumentos', 'principal']);
        $this->monedaOferta = $licitacion->moneda_presupuesto ?? 'CLP';
        
        // Inicializar slots de documentos para cada requisito
        foreach ($licitacion->requisitosDocumentos as $index => $requisito) {
            $this->documentosInfo[$index] = [
                'requisito_id' => $requisito->id,
                'nombre' => $requisito->nombre_requisito,
                'obligatorio' => $requisito->es_obligatorio,
                'archivo' => null,
            ];
        }
    }

    protected function rules()
    {
        $rules = [
            'montoOferta' => 'required|numeric|min:1',
            'monedaOferta' => 'required|in:CLP,UF,USD',
            'validezDias' => 'required|integer|min:1|max:365',
            'comentarios' => 'nullable|string|max:2000',
        ];

        // Validar documentos obligatorios
        foreach ($this->documentosInfo as $index => $info) {
            if ($info['obligatorio']) {
                $rules["documentosArchivos.{$index}"] = 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,zip,jpg,png';
            } else {
                $rules["documentosArchivos.{$index}"] = 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,zip,jpg,png';
            }
        }

        return $rules;
    }

    protected $messages = [
        'montoOferta.required' => 'El monto de la oferta es obligatorio.',
        'montoOferta.numeric' => 'El monto debe ser un número válido.',
        'montoOferta.min' => 'El monto debe ser mayor a 0.',
        'validezDias.required' => 'La validez de la oferta es obligatoria.',
        'validezDias.min' => 'La validez mínima es 1 día.',
        'documentosArchivos.*.required' => 'Este documento es obligatorio.',
        'documentosArchivos.*.max' => 'El archivo no puede superar 10MB.',
        'documentosArchivos.*.mimes' => 'Formato no permitido. Use: PDF, DOC, DOCX, XLS, XLSX, ZIP, JPG, PNG.',
    ];

    public function enviarOferta()
    {
        $this->validate();

        $user = auth()->user();

        // Crear la oferta
        $oferta = Oferta::create([
            'licitacion_id' => $this->licitacion->id,
            'contratista_id' => $user->empresa_contratista_id,
            'usuario_presenta_id' => $user->id,
            'fecha_presentacion' => now(),
            'monto_oferta_economica' => $this->montoOferta,
            'moneda_oferta' => $this->monedaOferta,
            'validez_oferta_dias' => $this->validezDias,
            'comentarios_oferta' => $this->comentarios,
            'estado_oferta' => 'pendiente_precalificacion_ryce',
        ]);

        // Guardar documentos
        foreach ($this->documentosArchivos as $index => $archivo) {
            if ($archivo) {
                $path = $archivo->store('ofertas/' . $oferta->id . '/documentos', 'public');
                
                DocumentoOferta::create([
                    'oferta_id' => $oferta->id,
                    'nombre_documento' => $this->documentosInfo[$index]['nombre'],
                    'descripcion_documento' => null,
                    'ruta_archivo' => $path,
                    'tipo_documento' => 'otro',
                ]);
            }
        }

        // Notificar al creador de la licitación
        Notificacion::crearNotificacion(
            $this->licitacion->usuario_creador_id,
            'nueva_oferta',
            'Nueva oferta recibida',
            "Se ha recibido una nueva oferta para la licitación \"{$this->licitacion->titulo}\".",
            $this->licitacion->id,
            'licitacion'
        );

        session()->flash('message', '¡Oferta enviada exitosamente! Puedes ver el estado en "Mis Postulaciones".');
        return redirect()->route('contratista.mis-ofertas');
    }

    public function render()
    {
        return view('livewire.contratista.ofertas.create');
    }
}
