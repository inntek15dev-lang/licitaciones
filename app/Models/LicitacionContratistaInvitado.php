<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicitacionContratistaInvitado extends Model
{
    use HasFactory;

    protected $table = 'licitacion_contratistas_invitados';

    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = ['licitacion_id', 'contratista_id'];

    protected $fillable = [
        'licitacion_id',
        'contratista_id',
        'estado_invitacion',
    ];

    protected $casts = [
        'fecha_invitacion' => 'datetime',
    ];

    /**
     * Estados de invitación.
     */
    const ESTADOS = [
        'enviada' => 'Enviada',
        'vista' => 'Vista',
        'aceptada' => 'Aceptada',
        'rechazada' => 'Rechazada',
    ];

    /**
     * Get the licitacion for the invitation.
     */
    public function licitacion(): BelongsTo
    {
        return $this->belongsTo(Licitacion::class, 'licitacion_id');
    }

    /**
     * Get the contratista for the invitation.
     */
    public function contratista(): BelongsTo
    {
        return $this->belongsTo(EmpresaContratista::class, 'contratista_id');
    }
}
