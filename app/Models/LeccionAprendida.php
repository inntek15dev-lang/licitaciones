<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeccionAprendida extends Model
{
    protected $table = 'lecciones_aprendidas';
    protected $fillable = ['licitacion_id', 'motivo_id', 'analisis_detalle'];
}
