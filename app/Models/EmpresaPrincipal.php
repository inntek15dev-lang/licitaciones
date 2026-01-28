<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpresaPrincipal extends Model
{
    use HasFactory;

    protected $table = 'empresas_principales';

    protected $fillable = [
        'razon_social',
        'rut',
        'direccion',
        'telefono',
        'email_contacto_principal',
        'persona_contacto_principal',
        'logo_url',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Get the usuarios associated with the EmpresaPrincipal.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'empresa_principal_id');
    }

    /**
     * Get the licitaciones owned by the EmpresaPrincipal.
     */
    public function licitaciones(): HasMany
    {
        return $this->hasMany(Licitacion::class, 'principal_id');
    }

    /**
     * Get the formularios de precalificación owned by the EmpresaPrincipal.
     */
    public function formulariosPrecalificacion(): HasMany
    {
        return $this->hasMany(FormularioPrecalificacion::class, 'empresa_principal_id');
    }
}
