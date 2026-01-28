<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoOferta extends Model
{
    use HasFactory;

    protected $table = 'documentos_oferta';

    public $timestamps = false;

    protected $fillable = [
        'oferta_id',
        'nombre_documento',
        'descripcion_documento',
        'ruta_archivo',
        'tipo_documento',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    /**
     * Tipos de documentos de oferta.
     */
    const TIPOS = [
        'propuesta_tecnica' => 'Propuesta Técnica',
        'propuesta_economica' => 'Propuesta Económica',
        'garantia_seriedad' => 'Garantía de Seriedad',
        'certificado' => 'Certificado',
        'otro' => 'Otro',
    ];

    /**
     * Get the oferta that owns the DocumentoOferta.
     */
    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }
}
