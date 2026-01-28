<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class FormularioPrecalificacion extends Model
{
    use HasFactory;

    protected $table = 'formularios_precalificacion';

    protected $fillable = [
        'empresa_principal_id',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Empresa Principal dueña del formulario.
     */
    public function empresaPrincipal(): BelongsTo
    {
        return $this->belongsTo(EmpresaPrincipal::class, 'empresa_principal_id');
    }

    /**
     * Requisitos asociados a este formulario (vía pivot).
     */
    public function requisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            CatalogoRequisitoPrecalificacion::class,
            'formulario_requisitos_precalificacion',
            'formulario_precalificacion_id',
            'catalogo_requisito_id'
        )
        ->withPivot(['obligatorio', 'orden'])
        ->withTimestamps()
        ->orderByPivot('orden');
    }

    /**
     * Licitaciones que usan este formulario.
     */
    public function licitaciones(): HasMany
    {
        return $this->hasMany(Licitacion::class, 'formulario_precalificacion_id');
    }

    /**
     * Scope para filtrar formularios activos.
     */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por empresa principal.
     */
    public function scopeDeEmpresa(Builder $query, int $empresaPrincipalId): Builder
    {
        return $query->where('empresa_principal_id', $empresaPrincipalId);
    }
}
