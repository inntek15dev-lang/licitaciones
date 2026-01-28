<?php

namespace App\Livewire\Admin\FormulariosPrecalificacion;

use App\Models\CatalogoRequisitoPrecalificacion;
use App\Models\EmpresaPrincipal;
use App\Models\FormularioPrecalificacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPrincipal = '';
    public $showModal = false;
    public $editMode = false;
    public $formularioId = null;

    // Form fields
    public $empresa_principal_id = '';
    public $nombre = '';
    public $descripcion = '';
    public $activo = true;
    public $selectedRequisitos = [];

    protected function rules()
    {
        $uniqueRule = 'unique:formularios_precalificacion,nombre';
        if ($this->editMode) {
            $uniqueRule .= ',' . $this->formularioId . ',id,empresa_principal_id,' . $this->empresa_principal_id;
        } else {
            $uniqueRule .= ',NULL,id,empresa_principal_id,' . $this->empresa_principal_id;
        }

        return [
            'empresa_principal_id' => 'required|exists:empresas_principales,id',
            'nombre' => ['required', 'string', 'max:255', $uniqueRule],
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
            'selectedRequisitos' => 'required|array|min:1',
            'selectedRequisitos.*' => 'exists:catalogo_requisitos_precalificacion,id',
        ];
    }

    protected $messages = [
        'empresa_principal_id.required' => 'Selecciona una empresa principal.',
        'nombre.required' => 'El nombre del formulario es obligatorio.',
        'nombre.unique' => 'Esta empresa ya tiene un formulario con este nombre.',
        'selectedRequisitos.required' => 'Debes seleccionar al menos un requisito.',
        'selectedRequisitos.min' => 'Debes seleccionar al menos un requisito.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPrincipal()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editMode = false;
        $this->formularioId = null;
        $this->empresa_principal_id = '';
        $this->nombre = '';
        $this->descripcion = '';
        $this->activo = true;
        $this->selectedRequisitos = [];
        $this->resetValidation();
    }

    public function edit($id)
    {
        $formulario = FormularioPrecalificacion::with('requisitos')->findOrFail($id);
        $this->editMode = true;
        $this->formularioId = $id;
        $this->empresa_principal_id = $formulario->empresa_principal_id;
        $this->nombre = $formulario->nombre;
        $this->descripcion = $formulario->descripcion;
        $this->activo = $formulario->activo;
        $this->selectedRequisitos = $formulario->requisitos->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $formulario = FormularioPrecalificacion::findOrFail($this->formularioId);
            $formulario->update([
                'empresa_principal_id' => $this->empresa_principal_id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'activo' => $this->activo,
            ]);

            // Sync requisitos
            $syncData = [];
            foreach ($this->selectedRequisitos as $orden => $requisitoId) {
                $syncData[$requisitoId] = ['obligatorio' => true, 'orden' => $orden];
            }
            $formulario->requisitos()->sync($syncData);

            session()->flash('message', 'Formulario actualizado correctamente.');
        } else {
            $formulario = FormularioPrecalificacion::create([
                'empresa_principal_id' => $this->empresa_principal_id,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'activo' => $this->activo,
            ]);

            // Attach requisitos
            foreach ($this->selectedRequisitos as $orden => $requisitoId) {
                $formulario->requisitos()->attach($requisitoId, ['obligatorio' => true, 'orden' => $orden]);
            }

            session()->flash('message', 'Formulario creado correctamente.');
        }

        $this->closeModal();
    }

    public function toggleActivo($id)
    {
        $formulario = FormularioPrecalificacion::findOrFail($id);
        $formulario->update(['activo' => !$formulario->activo]);
        
        $estado = $formulario->activo ? 'activado' : 'desactivado';
        session()->flash('message', "Formulario {$estado} correctamente.");
    }

    public function delete($id)
    {
        $formulario = FormularioPrecalificacion::findOrFail($id);
        
        // Check if used by any licitacion
        if ($formulario->licitaciones()->exists()) {
            session()->flash('error', 'No se puede eliminar: este formulario está siendo usado por licitaciones.');
            return;
        }
        
        $formulario->delete();
        session()->flash('message', 'Formulario eliminado correctamente.');
    }

    public function render()
    {
        $formularios = FormularioPrecalificacion::query()
            ->with(['empresaPrincipal', 'requisitos'])
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterPrincipal, function ($query) {
                $query->where('empresa_principal_id', $this->filterPrincipal);
            })
            ->orderBy('nombre')
            ->paginate(10);

        $empresas = EmpresaPrincipal::where('activo', true)->orderBy('razon_social')->get();
        $catalogoRequisitos = CatalogoRequisitoPrecalificacion::activos()->orderBy('nombre_requisito')->get();

        return view('livewire.admin.formularios-precalificacion.index', [
            'formularios' => $formularios,
            'empresas' => $empresas,
            'catalogoRequisitos' => $catalogoRequisitos,
        ]);
    }
}
