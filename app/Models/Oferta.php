<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Oferta extends Model
{
    use HasFactory;

    protected $table = 'ofertas';

    public $timestamps = false;

    protected $fillable = [
        'licitacion_id',
        'contratista_id',
        'usuario_presenta_id',
        'fecha_presentacion',
        'monto_oferta_economica',
        'moneda_oferta',
        'validez_oferta_dias',
        'comentarios_oferta',
        'estado_oferta',
        'comentarios_precalificacion_ryce',
        'usuario_precalificador_ryce_id',
        'fecha_precalificacion_ryce',
    ];

    protected $casts = [
        'fecha_presentacion' => 'datetime',
        'fecha_precalificacion_ryce' => 'datetime',
        'fecha_actualizacion_estado' => 'datetime',
        'monto_oferta_economica' => 'decimal:2',
    ];

    /**
     * Estados posibles de una oferta.
     */
    const ESTADOS = [
        'pendiente_precalificacion_ryce' => 'Pendiente Precalificación RyCE',
        'precalificada_por_ryce' => 'Precalificada por RyCE',
        'no_precalificada_ryce' => 'No Precalificada por RyCE',
        'presentada' => 'Presentada',
        'en_evaluacion_principal' => 'En Evaluación por Principal',
        'adjudicada' => 'Adjudicada',
        'no_adjudicada' => 'No Adjudicada',
        'retirada' => 'Retirada',
    ];

    /**
     * Get the licitacion that owns the Oferta.
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }

    /**
     * Get the contratista that owns the Oferta.
     */
    public function contratista(): BelongsTo
    {
        return $this->belongsTo(EmpresaContratista::class, 'contratista_id');
    }

    /**
     * Get the user that presented the Oferta.
     */
    public function usuarioPresenta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_presenta_id');
    }

    /**
     * Get the RyCE prequalifier for the Oferta.
     */
    public function usuarioPrecalificadorRyCE(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_precalificador_ryce_id');
    }

    /**
     * Get the documentos for the Oferta.
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoOferta::class, 'oferta_id');
    }
}
