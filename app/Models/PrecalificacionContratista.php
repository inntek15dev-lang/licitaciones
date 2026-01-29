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
        // Corporate Fields
        'nro_trabajadores',
        'anios_experiencia',
        'capital_social',
        'patrimonio_neto',
        'ventas_ultimo_anio',
        'moneda_financiera',
        'tasa_accidentabilidad',
        'tasa_siniestralidad',
        'tiene_programa_prevencion',
        'tiene_iso_9001',
        'tiene_iso_14001',
        'tiene_iso_45001',
        'nombre_representante_legal',
        'rut_representante_legal',
        // Advanced Matrix Fields
        'ind_liquidez',
        'ind_leverage',
        'monto_ebitda',
        'deuda_comercial_monto',
        'deuda_tributaria_al_dia',
        'hse_tat_anterior',
        'hse_tst_anterior',
        'hse_tat_actual',
        'hse_tst_actual',
        'cumple_legal_vigencia',
        'cumple_laboral_multas',
        'cumple_laboral_deuda',
        'score_ranking',
        'score_seguridad',
        'bloqueado_por_migracion'
    ];

    protected $casts = [
        'fecha_solicitud' => 'datetime',
        'fecha_resolucion' => 'datetime',
        'capital_social' => 'decimal:2',
        'patrimonio_neto' => 'decimal:2',
        'ventas_ultimo_anio' => 'decimal:2',
        'tasa_accidentabilidad' => 'decimal:2',
        'tasa_siniestralidad' => 'decimal:2',
        'tiene_programa_prevencion' => 'boolean',
        'tiene_iso_9001' => 'boolean',
        'tiene_iso_14001' => 'boolean',
        'tiene_iso_45001' => 'boolean',
        // Matrix Casts
        'ind_liquidez' => 'decimal:2',
        'ind_leverage' => 'decimal:2',
        'monto_ebitda' => 'decimal:2',
        'deuda_comercial_monto' => 'decimal:2',
        'deuda_tributaria_al_dia' => 'boolean',
        'hse_tat_anterior' => 'decimal:2',
        'hse_tst_anterior' => 'decimal:2',
        'hse_tat_actual' => 'decimal:2',
        'hse_tst_actual' => 'decimal:2',
        'cumple_legal_vigencia' => 'boolean',
        'cumple_laboral_multas' => 'boolean',
        'cumple_laboral_deuda' => 'boolean',
        'score_ranking' => 'decimal:2',
        'score_seguridad' => 'decimal:2',
        'bloqueado_por_migracion' => 'boolean',
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
