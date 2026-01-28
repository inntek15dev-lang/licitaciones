<?php

namespace App\Livewire\Admin\EmpresasContratistas;

use App\Models\EmpresaContratista;
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
    public $email_contacto = '';
    public $persona_contacto = '';
    public $sitio_web = '';
    public $activo = true;

    protected function rules()
    {
        return [
            'razon_social' => 'required|string|max:255',
            'rut' => 'required|string|max:20|unique:empresas_contratistas,rut' . ($this->empresaId ? ',' . $this->empresaId : ''),
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:25',
            'email_contacto' => 'nullable|email|max:100',
            'persona_contacto' => 'nullable|string|max:150',
            'sitio_web' => 'nullable|url|max:255',
            'activo' => 'boolean',
        ];
    }

    protected $messages = [
        'razon_social.required' => 'La razón social es obligatoria.',
        'rut.required' => 'El RUT es obligatorio.',
        'rut.unique' => 'Ya existe una empresa con este RUT.',
        'email_contacto.email' => 'El email no tiene un formato válido.',
        'sitio_web.url' => 'El sitio web debe ser una URL válida.',
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
        $this->email_contacto = '';
        $this->persona_contacto = '';
        $this->sitio_web = '';
        $this->activo = true;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $empresa = EmpresaContratista::findOrFail($id);
        $this->editMode = true;
        $this->empresaId = $id;
        $this->razon_social = $empresa->razon_social;
        $this->rut = $empresa->rut;
        $this->direccion = $empresa->direccion;
        $this->telefono = $empresa->telefono;
        $this->email_contacto = $empresa->email_contacto;
        $this->persona_contacto = $empresa->persona_contacto;
        $this->sitio_web = $empresa->sitio_web;
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
            'email_contacto' => $this->email_contacto,
            'persona_contacto' => $this->persona_contacto,
            'sitio_web' => $this->sitio_web,
            'activo' => $this->activo,
        ];

        if ($this->editMode) {
            $empresa = EmpresaContratista::findOrFail($this->empresaId);
            $empresa->update($data);
            session()->flash('message', 'Empresa actualizada correctamente.');
        } else {
            EmpresaContratista::create($data);
            session()->flash('message', 'Empresa creada correctamente.');
        }

        $this->closeModal();
    }

    public function toggleActivo($id)
    {
        $empresa = EmpresaContratista::findOrFail($id);
        $empresa->update(['activo' => !$empresa->activo]);
        session()->flash('message', 'Estado de la empresa actualizado.');
    }

    public function delete($id)
    {
        $empresa = EmpresaContratista::findOrFail($id);
        
        if ($empresa->usuarios()->count() > 0) {
            session()->flash('error', 'No se puede eliminar: la empresa tiene usuarios asociados.');
            return;
        }

        if ($empresa->ofertas()->count() > 0) {
            session()->flash('error', 'No se puede eliminar: la empresa tiene ofertas asociadas.');
            return;
        }

        $empresa->delete();
        session()->flash('message', 'Empresa eliminada correctamente.');
    }

    public function render()
    {
        $empresas = EmpresaContratista::query()
            ->withCount('usuarios', 'ofertas')
            ->when($this->search, function ($query) {
                $query->where('razon_social', 'like', '%' . $this->search . '%')
                      ->orWhere('rut', 'like', '%' . $this->search . '%');
            })
            ->orderBy('razon_social')
            ->paginate(10);

        return view('livewire.admin.empresas-contratistas.index', [
            'empresas' => $empresas,
        ]);
    }
}
