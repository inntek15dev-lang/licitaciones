<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    public $timestamps = false;

    protected $fillable = [
        'usuario_destino_id',
        'tipo_notificacion',
        'mensaje',
        'url_destino',
        'leida',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'leida' => 'boolean',
    ];

    /**
     * Tipos de notificación.
     */
    const TIPOS = [
        'LICITACION_APROBADA' => 'Licitación Aprobada',
        'LICITACION_OBSERVADA' => 'Licitación Observada',
        'NUEVA_CONSULTA_LICITACION' => 'Nueva Consulta en Licitación',
        'CONSULTA_RESPONDIDA' => 'Consulta Respondida',
        'OFERTA_PRECALIFICADA' => 'Oferta Precalificada',
        'OFERTA_NO_PRECALIFICADA' => 'Oferta No Precalificada',
        'OFERTA_ADJUDICADA' => 'Oferta Adjudicada',
        'OFERTA_NO_ADJUDICADA' => 'Oferta No Adjudicada',
    ];

    /**
     * Get the user that receives the Notificacion.
     */
    public function usuarioDestino(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_destino_id');
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    /**
     * Mark notification as read.
     */
    public function marcarComoLeida(): void
    {
        $this->update(['leida' => true]);
    }

    /**
     * Create a notification helper.
     */
    public static function crear(int $usuarioId, string $tipo, string $mensaje, ?string $url = null): self
    {
        return self::create([
            'usuario_destino_id' => $usuarioId,
            'tipo_notificacion' => $tipo,
            'mensaje' => $mensaje,
            'url_destino' => $url,
        ]);
    }

    /**
     * Crear notificación con formato completo.
     */
    public static function crearNotificacion(
        ?int $usuarioId,
        string $tipo,
        string $titulo,
        string $mensaje,
        ?int $entidadId = null,
        ?string $tipoEntidad = null
    ): ?self {
        if (!$usuarioId) {
            return null;
        }

        $url = null;
        if ($entidadId && $tipoEntidad === 'licitacion') {
            $url = route('principal.licitaciones.show', $entidadId);
        }

        return self::create([
            'usuario_destino_id' => $usuarioId,
            'tipo_notificacion' => $tipo,
            'mensaje' => "{$titulo}: {$mensaje}",
            'url_destino' => $url,
        ]);
    }
}
