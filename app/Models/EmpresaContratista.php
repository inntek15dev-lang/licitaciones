<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpresaContratista extends Model
{
    use HasFactory;

    protected $table = 'empresas_contratistas';

    protected $fillable = [
        'razon_social',
        'rut',
        'direccion',
        'telefono',
        'email_contacto_principal',
        'persona_contacto_principal',
        'rubros_especialidad',
        'documentacion_validada',
        'activo',
    ];

    protected $casts = [
        'documentacion_validada' => 'boolean',
        'activo' => 'boolean',
    ];

    /**
     * Get the usuarios associated with the EmpresaContratista.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'empresa_contratista_id');
    }

    /**
     * Get the ofertas submitted by the EmpresaContratista.
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'contratista_id');
    }

    /**
     * Get the consultas made by the EmpresaContratista.
     */
    public function consultas(): HasMany
    {
        return $this->hasMany(ConsultaRespuestaLicitacion::class, 'contratista_id');
    }
}
