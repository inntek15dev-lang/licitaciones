<?php

namespace App\Livewire\Admin\Categorias;

use App\Models\CategoriaLicitacion;
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
    public $categoriaId = null;

    // Form fields
    public $nombre_categoria = '';
    public $descripcion = '';

    protected $rules = [
        'nombre_categoria' => 'required|string|max:100|unique:categorias_licitaciones,nombre_categoria',
        'descripcion' => 'nullable|string',
    ];

    protected $messages = [
        'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
        'nombre_categoria.unique' => 'Ya existe una categoría con este nombre.',
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
        $this->categoriaId = null;
        $this->nombre_categoria = '';
        $this->descripcion = '';
        $this->resetValidation();
    }

    public function edit($id)
    {
        $categoria = CategoriaLicitacion::findOrFail($id);
        $this->editMode = true;
        $this->categoriaId = $id;
        $this->nombre_categoria = $categoria->nombre_categoria;
        $this->descripcion = $categoria->descripcion;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editMode) {
            $rules['nombre_categoria'] = 'required|string|max:100|unique:categorias_licitaciones,nombre_categoria,' . $this->categoriaId;
        }
        $this->validate($rules);

        if ($this->editMode) {
            $categoria = CategoriaLicitacion::findOrFail($this->categoriaId);
            $categoria->update([
                'nombre_categoria' => $this->nombre_categoria,
                'descripcion' => $this->descripcion,
            ]);
            session()->flash('message', 'Categoría actualizada correctamente.');
        } else {
            CategoriaLicitacion::create([
                'nombre_categoria' => $this->nombre_categoria,
                'descripcion' => $this->descripcion,
            ]);
            session()->flash('message', 'Categoría creada correctamente.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $categoria = CategoriaLicitacion::findOrFail($id);
        
        // Verificar si tiene licitaciones asociadas
        if ($categoria->licitaciones()->count() > 0) {
            session()->flash('error', 'No se puede eliminar: la categoría tiene licitaciones asociadas.');
            return;
        }

        $categoria->delete();
        session()->flash('message', 'Categoría eliminada correctamente.');
    }

    public function render()
    {
        $categorias = CategoriaLicitacion::query()
            ->when($this->search, function ($query) {
                $query->where('nombre_categoria', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nombre_categoria')
            ->paginate(10);

        return view('livewire.admin.categorias.index', [
            'categorias' => $categorias,
        ]);
    }
}
