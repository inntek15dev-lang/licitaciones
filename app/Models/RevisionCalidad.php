<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionCalidad extends Model
{
    protected $table = 'revisiones_calidad';
    protected $fillable = ['licitacion_id', 'contiene_errores', 'observaciones'];
}
