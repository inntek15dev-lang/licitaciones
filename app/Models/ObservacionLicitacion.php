<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservacionLicitacion extends Model
{
    use HasFactory;

    protected $table = 'observaciones_licitacion';

    public $timestamps = false;

    protected $fillable = [
        'licitacion_id',
        'usuario_revisor_id',
        'observacion',
        'fecha_observacion',
        'resuelta',
        'fecha_resolucion',
    ];

    protected $casts = [
        'fecha_observacion' => 'datetime',
        'fecha_resolucion' => 'datetime',
        'resuelta' => 'boolean',
    ];

    /**
     * Licitación relacionada.
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }

    /**
     * Usuario revisor que hizo la observación.
     */
    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_revisor_id');
    }

    /**
     * Marcar observación como resuelta.
     */
    public function marcarResuelta(): void
    {
        $this->update([
            'resuelta' => true,
            'fecha_resolucion' => now(),
        ]);
    }
}
