<?php

use App\Livewire\Dashboard;
use App\Livewire\Admin\Categorias\Index as CategoriasIndex;
use App\Livewire\Admin\EmpresasPrincipales\Index as EmpresasPrincipalesIndex;
use App\Livewire\Admin\EmpresasContratistas\Index as EmpresasContratistasIndex;
use App\Livewire\Admin\Licitaciones\Index as AdminLicitacionesIndex;
use App\Livewire\Admin\Licitaciones\Show as AdminLicitacionesShow;
use App\Livewire\Admin\Licitaciones\Create as AdminLicitacionesCreate;
use App\Livewire\Admin\CatalogoRequisitos\Index as CatalogoRequisitosIndex;
use App\Livewire\Admin\FormulariosPrecalificacion\Index as FormulariosPrecalificacionIndex;
use App\Livewire\Principal\Licitaciones\Index as LicitacionesIndex;
use App\Livewire\Principal\Licitaciones\Create as LicitacionesCreate;
use App\Livewire\Principal\Licitaciones\Show as PrincipalLicitacionesShow;
use App\Livewire\Principal\Licitaciones\Edit as LicitacionesEdit;
use App\Livewire\Contratista\Licitaciones\Index as ContratistaLicitacionesIndex;
use App\Livewire\Contratista\Licitaciones\Show as ContratistaLicitacionesShow;
use App\Livewire\Contratista\Licitaciones\SolicitudPrecalificacion;
use App\Livewire\Contratista\Ofertas\Index as ContratistaOfertasIndex;
use App\Livewire\Contratista\Ofertas\Create as ContratistaOfertasCreate;
use App\Livewire\Admin\Precalificaciones\Index as AdminPrecalificacionesIndex;
use App\Livewire\Admin\Precalificaciones\Revisar as AdminPrecalificacionesRevisar;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Rutas de Administración (Admin RyCE)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('categorias', CategoriasIndex::class)->name('categorias');
    Route::get('empresas-principales', EmpresasPrincipalesIndex::class)->name('empresas-principales');
    Route::get('empresas-contratistas', EmpresasContratistasIndex::class)->name('empresas-contratistas');
    Route::get('catalogo-requisitos', CatalogoRequisitosIndex::class)->name('catalogo-requisitos');
    Route::get('formularios-precalificacion', FormulariosPrecalificacionIndex::class)->name('formularios-precalificacion');
    Route::get('licitaciones', AdminLicitacionesIndex::class)->name('licitaciones');
    Route::get('licitaciones/crear', AdminLicitacionesCreate::class)->name('licitaciones.create');
    Route::get('licitaciones/{id}', AdminLicitacionesShow::class)->name('licitaciones.show');
    
    // Precalificaciones
    Route::get('precalificaciones', AdminPrecalificacionesIndex::class)->name('precalificaciones');
    Route::get('precalificaciones/{precalificacion}', AdminPrecalificacionesRevisar::class)->name('precalificaciones.revisar');
});

// Rutas de Principal (Empresas Principales)
Route::middleware(['auth', 'verified'])->prefix('principal')->name('principal.')->group(function () {
    Route::get('licitaciones', LicitacionesIndex::class)->name('licitaciones');
    Route::get('licitaciones/crear', LicitacionesCreate::class)->name('licitaciones.create');
    Route::get('licitaciones/{licitacion}/editar', LicitacionesEdit::class)->name('licitaciones.edit');
    Route::get('licitaciones/{licitacion}', PrincipalLicitacionesShow::class)->name('licitaciones.show');
});

// Rutas de Contratista (Empresas Contratistas)
Route::middleware(['auth', 'verified'])->prefix('contratista')->name('contratista.')->group(function () {
    Route::get('licitaciones', ContratistaLicitacionesIndex::class)->name('licitaciones');
    Route::get('licitaciones/{licitacion}', ContratistaLicitacionesShow::class)->name('licitaciones.show');
    Route::get('licitaciones/{licitacion}/precalificar', SolicitudPrecalificacion::class)->name('licitaciones.precalificar');
    Route::get('licitaciones/{licitacion}/postular', ContratistaOfertasCreate::class)->name('licitaciones.postular');
    Route::get('mis-ofertas', ContratistaOfertasIndex::class)->name('mis-ofertas');
    Route::get('ofertas', ContratistaOfertasIndex::class)->name('ofertas');
});

require __DIR__.'/auth.php';

