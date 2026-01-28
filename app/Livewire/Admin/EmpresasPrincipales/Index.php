<?php

namespace App\Livewire\Admin\EmpresasPrincipales;

use App\Models\EmpresaPrincipal;
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
    public $empresaId = null;

    // Form fields
    public $razon_social = '';
    public $rut = '';
    public $direccion = '';
    public $telefono = '';
    public $email_contacto_principal = '';
    public $persona_contacto_principal = '';
    public $activo = true;

    protected function rules()
    {
        return [
            'razon_social' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:empresas_principales,rut' . ($this->empresaId ? ',' . $this->empresaId : ''),
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:25',
            'email_contacto_principal' => 'nullable|email|max:100',
            'persona_contacto_principal' => 'nullable|string|max:150',
            'activo' => 'boolean',
        ];
    }

    protected $messages = [
        'razon_social.required' => 'La razón social es obligatoria.',
        'rut.required' => 'El RUT es obligatorio.',
        'rut.unique' => 'Ya existe una empresa con este RUT.',
        'email_contacto_principal.email' => 'El email no tiene un formato válido.',
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
        $this->empresaId = null;
        $this->razon_social = '';
        $this->rut = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->email_contacto_principal = '';
        $this->persona_contacto_principal = '';
        $this->activo = true;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $empresa = EmpresaPrincipal::findOrFail($id);
        $this->editMode = true;
        $this->empresaId = $id;
        $this->razon_social = $empresa->razon_social;
        $this->rut = $empresa->rut;
        $this->direccion = $empresa->direccion;
        $this->telefono = $empresa->telefono;
        $this->email_contacto_principal = $empresa->email_contacto_principal;
        $this->persona_contacto_principal = $empresa->persona_contacto_principal;
        $this->activo = $empresa->activo;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'razon_social' => $this->razon_social,
            'rut' => $this->rut,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email_contacto_principal' => $this->email_contacto_principal,
            'persona_contacto_principal' => $this->persona_contacto_principal,
            'activo' => $this->activo,
        ];

        if ($this->editMode) {
            $empresa = EmpresaPrincipal::findOrFail($this->empresaId);
            $empresa->update($data);
            session()->flash('message', 'Empresa actualizada correctamente.');
        } else {
            EmpresaPrincipal::create($data);
            session()->flash('message', 'Empresa creada correctamente.');
        }

        $this->closeModal();
    }

    public function toggleActivo($id)
    {
        $empresa = EmpresaPrincipal::findOrFail($id);
        $empresa->update(['activo' => !$empresa->activo]);
        session()->flash('message', 'Estado de la empresa actualizado.');
    }

    public function delete($id)
    {
        $empresa = EmpresaPrincipal::findOrFail($id);
        
        if ($empresa->usuarios()->count() > 0) {
            session()->flash('error', 'No se puede eliminar: la empresa tiene usuarios asociados.');
            return;
        }

        if ($empresa->licitaciones()->count() > 0) {
            session()->flash('error', 'No se puede eliminar: la empresa tiene licitaciones asociadas.');
            return;
        }

        $empresa->delete();
        session()->flash('message', 'Empresa eliminada correctamente.');
    }

    public function render()
    {
        $empresas = EmpresaPrincipal::query()
            ->withCount('usuarios', 'licitaciones')
            ->when($this->search, function ($query) {
                $query->where('razon_social', 'like', '%' . $this->search . '%')
                      ->orWhere('rut', 'like', '%' . $this->search . '%');
            })
            ->orderBy('razon_social')
            ->paginate(10);

        return view('livewire.admin.empresas-principales.index', [
            'empresas' => $empresas,
        ]);
    }
}
