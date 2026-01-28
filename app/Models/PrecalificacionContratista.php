<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrecalificacionContratista extends Model
{
    use HasFactory;

    protected $table = 'precalificaciones_contratistas';

    protected $fillable = [
        'licitacion_id',
        'contratista_id',
        'estado',
        'fecha_solicitud',
        'fecha_resolucion',
        'revisado_por_usuario_id',
        'tipo_revisor',
        'motivo_rechazo',
        'comentarios_contratista',
        'comentarios_rectificacion',
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_resolucion' => 'datetime',
    ];

    /**
     * Estados posibles de precalificación
     */
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_APROBADA = 'aprobada';
    const ESTADO_RECHAZADA = 'rechazada';
    const ESTADO_RECTIFICANDO = 'rectificando';

    const ESTADOS = [
        self::ESTADO_PENDIENTE => 'Pendiente',
        self::ESTADO_APROBADA => 'Aprobada',
        self::ESTADO_RECHAZADA => 'Rechazada',
        self::ESTADO_RECTIFICANDO => 'Rectificando',
    ];

    /**
     * Relación con la licitación
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }

    /**
     * Relación con la empresa contratista
     */
    public function contratista(): BelongsTo
    {
        return $this->belongsTo(EmpresaContratista::class, 'contratista_id');
    }

    /**
     * Relación con el usuario que revisó
     */
    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por_usuario_id');
    }

    /**
     * Scope: Solo pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope: Solo aprobadas
     */
    public function scopeAprobadas($query)
    {
        return $query->where('estado', self::ESTADO_APROBADA);
    }

    /**
     * Scope: Solo rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADA);
    }

    /**
     * Scope: Rectificando
     */
    public function scopeRectificando($query)
    {
        return $query->where('estado', self::ESTADO_RECTIFICANDO);
    }

    /**
     * Scope: Para revisar (pendientes + rectificando)
     */
    public function scopeParaRevisar($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_RECTIFICANDO]);
    }

    /**
     * Verificar si está aprobada
     */
    public function estaAprobada(): bool
    {
        return $this->estado === self::ESTADO_APROBADA;
    }

    /**
     * Verificar si está rechazada
     */
    public function estaRechazada(): bool
    {
        return $this->estado === self::ESTADO_RECHAZADA;
    }

    /**
     * Verificar si está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verificar si está rectificando
     */
    public function estaRectificando(): bool
    {
        return $this->estado === self::ESTADO_RECTIFICANDO;
    }
}
