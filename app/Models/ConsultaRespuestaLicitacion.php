<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultaRespuestaLicitacion extends Model
{
    use HasFactory;

    protected $table = 'consultas_respuestas_licitacion';

    public $timestamps = false;

    protected $fillable = [
        'licitacion_id',
        'contratista_id',
        'usuario_pregunta_id',
        'texto_pregunta',
        'usuario_respuesta_id',
        'texto_respuesta',
        'fecha_respuesta',
        'es_publica',
        'documento_adjunto_respuesta_id',
    ];

    protected $casts = [
        'fecha_pregunta' => 'datetime',
        'fecha_respuesta' => 'datetime',
        'es_publica' => 'boolean',
    ];

    /**
     * Get the licitacion that owns the ConsultaRespuestaLicitacion.
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }

    /**
     * Get the contratista that made the ConsultaRespuestaLicitacion.
     */
    public function contratista(): BelongsTo
    {
        return $this->belongsTo(EmpresaContratista::class, 'contratista_id');
    }

    /**
     * Get the user that asked the question.
     */
    public function usuarioPregunta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_pregunta_id');
    }

    /**
     * Get the user that answered the question.
     */
    public function usuarioRespuesta(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_respuesta_id');
    }

    /**
     * Scope for unanswered questions.
     */
    public function scopePendientes($query)
    {
        return $query->whereNull('texto_respuesta');
    }

    /**
     * Scope for public questions.
     */
    public function scopePublicas($query)
    {
        return $query->where('es_publica', true);
    }
}
