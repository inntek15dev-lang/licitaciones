<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoLicitacion extends Model
{
    use HasFactory;

    protected $table = 'documentos_licitacion';

    public $timestamps = false;

    protected $fillable = [
        'licitacion_id',
        'nombre_documento',
        'descripcion_documento',
        'ruta_archivo',
        'tipo_documento',
        'es_precalificacion',
        'subido_por_usuario_id',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    /**
     * Tipos de documentos de licitación.
     */
    const TIPOS = [
        'bases' => 'Bases',
        'anexo_tecnico' => 'Anexo Técnico',
        'anexo_economico' => 'Anexo Económico',
        'plano' => 'Plano',
        'aclaracion' => 'Aclaración',
        'otro' => 'Otro',
    ];

    /**
     * Get the licitacion that owns the DocumentoLicitacion.
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }

    /**
     * Get the user that uploaded the DocumentoLicitacion.
     */
    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por_usuario_id');
    }
}
