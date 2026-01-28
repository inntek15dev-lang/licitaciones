<?php

namespace App\Livewire\Admin\CatalogoRequisitos;

use App\Models\CatalogoRequisitoPrecalificacion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $requisitoId = null;

    // Form fields
    public $nombre_requisito = '';
    public $criterio_cumplimiento = '';
    public $activo = true;

    protected $rules = [
        'nombre_requisito' => 'required|string|max:255|unique:catalogo_requisitos_precalificacion,nombre_requisito',
        'criterio_cumplimiento' => 'nullable|string',
        'activo' => 'boolean',
    ];

    protected $messages = [
        'nombre_requisito.required' => 'El nombre del requisito es obligatorio.',
        'nombre_requisito.unique' => 'Ya existe un requisito con este nombre.',
        'nombre_requisito.max' => 'El nombre no puede exceder 255 caracteres.',
    ];

    public function updatingSearch()
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
        $this->requisitoId = null;
        $this->nombre_requisito = '';
        $this->criterio_cumplimiento = '';
        $this->activo = true;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $requisito = CatalogoRequisitoPrecalificacion::findOrFail($id);
        $this->editMode = true;
        $this->requisitoId = $id;
        $this->nombre_requisito = $requisito->nombre_requisito;
        $this->criterio_cumplimiento = $requisito->criterio_cumplimiento;
        $this->activo = $requisito->activo;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editMode) {
            $rules['nombre_requisito'] = 'required|string|max:255|unique:catalogo_requisitos_precalificacion,nombre_requisito,' . $this->requisitoId;
        }
        $this->validate($rules);

        if ($this->editMode) {
            $requisito = CatalogoRequisitoPrecalificacion::findOrFail($this->requisitoId);
            $requisito->update([
                'nombre_requisito' => $this->nombre_requisito,
                'criterio_cumplimiento' => $this->criterio_cumplimiento,
                'activo' => $this->activo,
            ]);
            session()->flash('message', 'Requisito actualizado correctamente.');
        } else {
            CatalogoRequisitoPrecalificacion::create([
                'nombre_requisito' => $this->nombre_requisito,
                'criterio_cumplimiento' => $this->criterio_cumplimiento,
                'activo' => $this->activo,
            ]);
            session()->flash('message', 'Requisito creado correctamente.');
        }

        $this->closeModal();
    }

    public function toggleActivo($id)
    {
        $requisito = CatalogoRequisitoPrecalificacion::findOrFail($id);
        $requisito->update(['activo' => !$requisito->activo]);
        
        $estado = $requisito->activo ? 'activado' : 'desactivado';
        session()->flash('message', "Requisito {$estado} correctamente.");
    }

    public function delete($id)
    {
        $requisito = CatalogoRequisitoPrecalificacion::findOrFail($id);
        $requisito->delete();
        session()->flash('message', 'Requisito eliminado correctamente.');
    }

    public function render()
    {
        $requisitos = CatalogoRequisitoPrecalificacion::query()
            ->when($this->search, function ($query) {
                $query->where('nombre_requisito', 'like', '%' . $this->search . '%')
                      ->orWhere('criterio_cumplimiento', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nombre_requisito')
            ->paginate(10);

        return view('livewire.admin.catalogo-requisitos.index', [
            'requisitos' => $requisitos,
        ]);
    }
}
