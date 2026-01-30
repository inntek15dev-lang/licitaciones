<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatMotivoRechazo extends Model
{
    protected $table = 'cat_motivos_rechazo';
    protected $fillable = ['id', 'motivo', 'etapa_aplicable'];
}
