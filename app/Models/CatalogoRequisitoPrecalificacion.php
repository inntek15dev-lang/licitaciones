<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CatalogoRequisitoPrecalificacion extends Model
{
    use HasFactory;

    protected $table = 'catalogo_requisitos_precalificacion';

    protected $fillable = [
        'nombre_requisito',
        'criterio_cumplimiento',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Scope para filtrar solo requisitos activos.
     */
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
