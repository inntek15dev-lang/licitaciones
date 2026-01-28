<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Licitacion extends Model
{
    use HasFactory;

    protected $table = 'licitaciones';

    protected $fillable = [
        'principal_id',
        'usuario_creador_id',
        'codigo_licitacion',
        'titulo',
        'descripcion_corta',
        'descripcion_larga',
        'tipo_licitacion',
        'estado',
        'fecha_publicacion',
        'fecha_inicio_consultas',
        'fecha_cierre_consultas',
        'fecha_inicio_recepcion_ofertas',
        'fecha_cierre_recepcion_ofertas',
        'fecha_adjudicacion_estimada',
        'fecha_adjudicacion_real',
        'presupuesto_referencial',
        'moneda_presupuesto',
        'lugar_ejecucion_trabajos',
        'requiere_visita_terreno',
        'fecha_visita_terreno',
        'contacto_visita_terreno',
        'comentarios_revision_ryce',
        'usuario_revisor_ryce_id',
        'motivo_cancelacion',
        'motivo_desierta',
        // Precalificación
        'requiere_precalificacion',
        'responsable_precalificacion',
        'formulario_precalificacion_id',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
        'fecha_inicio_consultas' => 'datetime',
        'fecha_cierre_consultas' => 'datetime',
        'fecha_inicio_recepcion_ofertas' => 'datetime',
        'fecha_cierre_recepcion_ofertas' => 'datetime',
        'fecha_adjudicacion_estimada' => 'date',
        'fecha_adjudicacion_real' => 'datetime',
        'requiere_visita_terreno' => 'boolean',
        'fecha_visita_terreno' => 'datetime',
        'presupuesto_referencial' => 'decimal:2',
        'requiere_precalificacion' => 'boolean',
    ];

    /**
     * Estados posibles de una licitación.
     */
    const ESTADOS = [
        'borrador' => 'Borrador',
        'lista_para_publicar' => 'Lista para Publicar',
        'observada_por_ryce' => 'Observada por RyCE',
        'publicada' => 'Publicada',
        'cerrada_consultas' => 'Cerrada Consultas',
        'cerrada_ofertas' => 'Cerrada Ofertas',
        'en_evaluacion' => 'En Evaluación',
        'adjudicada' => 'Adjudicada',
        'desierta' => 'Desierta',
        'cancelada' => 'Cancelada',
    ];

    /**
     * Get the empresa principal that owns the Licitacion.
     */
    public function principal(): BelongsTo
    {
        return $this->belongsTo(EmpresaPrincipal::class, 'principal_id');
    }

    /**
     * Get the user that created the Licitacion.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    /**
     * Get the RyCE reviewer for the Licitacion.
     */
    public function revisorRyCE(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_revisor_ryce_id');
    }

    /**
     * The categorias that belong to the Licitacion.
     */
    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(
            CategoriaLicitacion::class,
            'licitacion_categorias',
            'licitacion_id',
            'categoria_id'
        );
    }

    /**
     * Get the documentos for the Licitacion.
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoLicitacion::class, 'licitacion_id');
    }

    /**
     * Get the requisitos_documentos for the Licitacion.
     */
    public function requisitosDocumentos(): HasMany
    {
        return $this->hasMany(RequisitoDocumentoLicitacion::class, 'licitacion_id');
    }

    /**
     * Get the ofertas for the Licitacion.
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'licitacion_id');
    }

    /**
     * Get the consultas_respuestas for the Licitacion.
     */
    public function consultasRespuestas(): HasMany
    {
        return $this->hasMany(ConsultaRespuestaLicitacion::class, 'licitacion_id');
    }

    /**
     * Get the observaciones (historial) for the Licitacion.
     */
    public function observaciones(): HasMany
    {
        return $this->hasMany(ObservacionLicitacion::class, 'licitacion_id')->orderBy('fecha_observacion', 'desc');
    }

    /**
     * Get the contratistas invitados for the Licitacion (privadas).
     */
    public function contratistasInvitados(): HasMany
    {
        return $this->hasMany(LicitacionContratistaInvitado::class, 'licitacion_id');
    }

    /**
     * Get the formulario de precalificación asociado.
     */
    public function formularioPrecalificacion(): BelongsTo
    {
        return $this->belongsTo(FormularioPrecalificacion::class, 'formulario_precalificacion_id');
    }

    /**
     * Check if licitación está en periodo de consultas.
     */
    public function enPeriodoConsultas(): bool
    {
        $now = now();
        return $this->estado === 'publicada'
            && $this->fecha_inicio_consultas <= $now
            && $this->fecha_cierre_consultas >= $now;
    }

    /**
     * Check if licitación acepta ofertas.
     */
    public function aceptaOfertas(): bool
    {
        $now = now();
        return $this->estado === 'publicada'
            && $this->fecha_inicio_recepcion_ofertas <= $now
            && $this->fecha_cierre_recepcion_ofertas >= $now;
    }

    /**
     * Relación con precalificaciones de contratistas
     */
    public function precalificaciones(): HasMany
    {
        return $this->hasMany(PrecalificacionContratista::class, 'licitacion_id');
    }

    /**
     * Verificar si un contratista puede postular a esta licitación
     * Si requiere precalificación, el contratista debe estar aprobado
     */
    public function puedePostular(EmpresaContratista $contratista): bool
    {
        // Si no requiere precalificación, puede postular directamente
        if (!$this->requiere_precalificacion) {
            return true;
        }

        // Si requiere precalificación, verificar si está aprobado
        return $this->precalificaciones()
            ->where('contratista_id', $contratista->id)
            ->where('estado', PrecalificacionContratista::ESTADO_APROBADA)
            ->exists();
    }

    /**
     * Obtener la precalificación de un contratista para esta licitación
     */
    public function getPrecalificacion(EmpresaContratista $contratista): ?PrecalificacionContratista
    {
        return $this->precalificaciones()
            ->where('contratista_id', $contratista->id)
            ->first();
    }
}
