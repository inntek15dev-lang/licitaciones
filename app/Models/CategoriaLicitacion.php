<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CategoriaLicitacion extends Model
{
    use HasFactory;

    protected $table = 'categorias_licitaciones';

    protected $fillable = [
        'nombre_categoria',
        'descripcion',
    ];

    /**
     * The licitaciones that belong to the CategoriaLicitacion.
     */
    public function licitaciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Licitacion::class,
            'licitacion_categorias',
            'categoria_id',
            'licitacion_id'
        );
    }
}
