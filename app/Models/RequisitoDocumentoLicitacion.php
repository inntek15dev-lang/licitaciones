<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitoDocumentoLicitacion extends Model
{
    use HasFactory;

    protected $table = 'requisitos_documentos_licitacion';

    protected $fillable = [
        'licitacion_id',
        'nombre_requisito',
        'descripcion_requisito',
        'es_obligatorio',
        'es_precalificacion',
        'orden',
    ];

    protected $casts = [
        'es_obligatorio' => 'boolean',
    ];

    /**
     * Get the licitacion that owns the RequisitoDocumentoLicitacion.
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }
}
